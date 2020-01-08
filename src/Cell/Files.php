<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Cell;
use Quid\Base;
use Quid\Core;
use Quid\Main;
use Quid\Orm;

// files
// abstract class extended by the media and medias cells
abstract class Files extends Core\CellAlias
{
    // config
    public static $config = [];


    // commonCast
    // cast la cellule, retourne le path http ou base64
    final protected function commonCast():?string
    {
        $return = null;
        $index = ($this->hasIndex())? 0:null;
        $version = ($this->hasVersion())? 1:null;
        $file = $this->commonFile($index,$version);

        if(!empty($file))
        $return = $file->pathToUriOrBase64();

        return $return;
    }


    // canBeDeleted
    // retourne vrai si le média peut être effacé
    public function canBeDeleted(?int $index=null):bool
    {
        return ($this->table()->hasPermission('mediaDelete') && $this->fileExists($index))? true:false;
    }


    // checkCanBeDeleted
    // retourne vrai si le média peut être effacé, sinon envoie une exception
    final public function checkCanBeDeleted(?int $index=null):bool
    {
        $return = $this->canBeDeleted($index);

        if($return === false)
        static::throw('cannotDelete',$index);

        return $return;
    }


    // canBeRegenerated
    // retourne vrai si le média peut être regénéré
    // doit avoir des versions
    final public function canBeRegenerated(?int $index=null):bool
    {
        return ($this->hasVersion() && $this->table()->hasPermission('mediaRegenerate') && $this->fileExists($index))? true:false;
    }


    // checkCanBeRegenerated
    // retourne vrai si le média peut être effacé, sinon envoie une exception
    final public function checkCanBeRegenerated(?int $index=null):bool
    {
        $return = $this->canBeRegenerated($index);

        if($return === false)
        static::throw('cannotRegenerate',$index);

        return $return;
    }


    // commonBasename
    // retourne un basename de fichier dans la cellule
    final protected function commonBasename(?int $index=null,$version=null):?string
    {
        $return = null;
        $get = $this->get();

        if($this->hasIndex())
        {
            $index = $this->checkIndex($index);
            if(is_array($get) && array_key_exists($index,$get))
            $get = $get[$index];
        }

        if(is_string($get) && strlen($get))
        {
            $return = Base\Path::safeBasename($get);

            if(is_scalar($version) && $this->hasVersion())
            {
                $extension = $this->commonVersionExtension($index,$version);
                $return = Base\Path::changeBasenameExtension($extension,$return);
            }
        }

        return $return;
    }


    // commonCellPath
    // retourne le chemin de la cellule qui combine le nom de la table, le id de la ligne, le nom de la colonne
    // possible de fournir l'index, n'affiche pas si false
    // possible de fournir une version, n'affiche pas si false
    // si null inclut la version original (0)
    final protected function commonCellPath($index=null,$version=null):string
    {
        $return = null;
        $array = [$this->tableName(),$this->rowPrimary(),$this->name()];
        $hasIndex = $this->hasIndex();

        if($hasIndex && $index !== false)
        $array[] = $this->checkIndex($index);

        $return = Base\Path::append($array);

        if(($hasIndex === false || $index !== false) && $version !== false)
        {
            $version = $this->col()->versionKey($version);

            if(is_scalar($version) && $this->hasVersion())
            $return = Base\Path::append($return,$version);
        }

        return $return;
    }


    // commonCellPathBasename
    // retourne le chemin de la cellule avec le basename et l'index
    // le fichier n'a pas besoin d'exister
    final protected function commonCellPathBasename(?int $index=null,$version=null):?string
    {
        $return = null;
        $basename = $this->commonBasename($index,$version);

        if(!empty($basename))
        $return = Base\Path::append($this->commonCellPath($index,$version),$basename);

        return $return;
    }


    // commonBasePath
    // retourne le base path à l'index qui est une combinaison du rootPath et cellPath
    final protected function commonBasePath($index=null,$version=null):string
    {
        return Base\Path::append($this->rootPath(),$this->commonCellPath($index,$version));
    }


    // commonFilePath
    // retourne le file path à l'index qui combine le basePath et le basename qui est la valeur de la cellule
    final protected function commonFilePath(?int $index=null,$version=null):?string
    {
        $return = null;
        
        if($this->isNotEmpty())
        $return = Base\Path::append($this->commonBasePath($index,$version),$this->commonBasename($index,$version));

        return $return;
    }


    // commonThrow
    // utilisé pour envoyer une exception avec l'index et la version
    final protected function commonThrow(?int $index=null,$version=null):void
    {
        $array = [$this->table(),$this->row(),$this->col()];
        if($this->hasIndex())
        $array[] = $index;

        $array[] = $version;
        static::throw(...$array);

        return;
    }


    // commonFileExists
    // retourne vrai si le fichier à l'index existe
    final protected function commonFileExists(?int $index=null,$version=null):bool
    {
        return static::pathExists($this->commonFilePath($index,$version));
    }


    // commonCheckFileExists
    // envoie une exception si le fichier à l'index n'existe pas
    final protected function commonCheckFileExists(?int $index=null,$version=null):self
    {
        if(!$this->commonFileExists($index,$version))
        $this->commonThrow($index,$version);

        return $this;
    }


    // commonFile
    // retourne l'objet fichier
    // peut retourner null
    final protected function commonFile(?int $index=null,$version=null):?Main\File
    {
        $return = null;
        $path = $this->commonFilePath($index,$version);
        
        if(static::pathExists($path))
        {
            $return = Main\File::new($path);
            $return->setAttr('defaultAlt',$this->row()->cellName());
        }

        return $return;
    }

    
    // commonCheckFile
    // retourne l'objet fichier, envoie une exception si non existant
    final protected function commonCheckFile(?int $index=null,$version=null):Main\File
    {
        $return = $this->commonFile($index,$version);

        if(empty($return))
        $return = $this->commonThrow($index,$version);

        return $return;
    }


    // commonVersionExtension
    // retourne l'extension a utilisé pour la version à l'index donné
    final protected function commonVersionExtension(?int $index=null,$version=null,bool $exception=true):string
    {
        $return = null;
        $col = $this->col();
        $version = $col->version($version,$exception);
        
        if(is_array($version))
        {
            if(is_string($version['convert']))
            $return = $version['convert'];

            else
            {
                $path = $this->commonFilePath($index);
                
                if(!empty($path))
                $return = Base\Path::extension($path);
            }
        }

        if(empty($return))
        $return = $col->defaultConvertExtension();

        return $return;
    }


    // commonVersion
    // retourne un objet files avec toutes les versions pour un index
    // retourne null si pas de version
    final protected function commonVersion(?int $index=null):?Main\Files
    {
        $return = null;

        if($this->isNotEmpty() && $this->hasVersion())
        {
            $return = Main\Files::newOverload();
            $col = $this->col();

            foreach ($col->versions() as $key => $value)
            {
                $path = $this->commonFilePath($index,$key);

                if(Base\File::is($path))
                $return->set($key,$path);
            }
        }

        return $return;
    }


    // commonDownloadRoute
    // retourne la route pour le téléchargement
    final protected function commonDownloadRoute(?int $index=null):Core\Route
    {
        $return = null;
        $col = $this->col();
        $array = ['table'=>$this,'primary'=>$this,'col'=>$this];

        if($this->hasIndex())
        $array['index'] = $index;

        $return = $col->route('download',$array);

        return $return;
    }


    // commonMakeVersion
    // reconstruit les versions pour un ou plusieurs index à partir de l'image originale
    // efface tous les dossiers sauf celui de l'original (donc efface contenu de version inexistante)
    final protected function commonMakeVersion($indexes=null,?array $option=null):array
    {
        $return = [];
        $option = Base\Arr::plus(['com'=>false],$option);
        $col = $this->col();
        $versions = $col->versions();

        if($this->hasIndex())
        {
            if($indexes === true)
            $indexes = $this->indexesExists();

            else
            $indexes = (array) $indexes;
        }

        else
        $indexes = [null];

        if(is_array($versions) && !empty($versions))
        {
            foreach ($indexes as $index)
            {
                $file = $this->commonCheckFile($index);
                $isImage = ($file instanceof Main\File\Image)? true:false;

                $indexDirname = $this->commonBasePath($index,false);
                $originalDirname = $this->commonBasePath($index,null);

                if(!empty($indexDirname) && !empty($originalDirname))
                {
                    foreach (Base\Dir::get($indexDirname) as $path)
                    {
                        if($path !== $originalDirname)
                        {
                            if(Base\Dir::emptyAndUnlink($path) !== true)
                            static::throw('cannotUnlink',$path);
                        }
                    }

                    foreach ($versions as $key => $version)
                    {
                        $path = $this->commonFilePath($index,$key);
                        $dirname = $this->commonBasePath($index,$key);

                        if($isImage === true)
                        $r = $file->compress($dirname,null,$version);

                        else
                        $r = $this->commonCopy($file,$path,$dirname);

                        $return[$path] = $r;
                    }
                }

                else
                static::throw();
            }

            $count = count($return);
            if($option['com'] === true && $count > 0)
            {
                $text = static::langPlural($count,'com/pos/media/regenerate',['count'=>$count]);
                $this->com($text,'pos');
            }
        }

        return $return;
    }


    // commonCopy
    // utilisé à deux endroits pour faire la copie d'un fichier
    // peut envoyer une exception si dirname n'est pas writable ou créable
    final protected function commonCopy(Main\File $file,string $path,string $dirname):bool
    {
        $return = false;

        if(!Base\Dir::setOrWritable($dirname))
        static::throw('cannnotCreateFolderStructure');

        $file->copy($path);
        $return = Base\File::is($path);

        return $return;
    }


    // commonProcess
    // lance le process de déplacement du média lié
    // efface le dossier de l'original avant de le recréer
    final protected function commonProcess(?int $index=null,Main\File $new):void
    {
        $this->col()->checkWritable();
        $dirname = $this->commonBasePath($index);
        $deleteSource = $new->getAttr('uploadDeleteSource');

        if(Base\Dir::is($dirname))
        Base\Dir::emptyAndUnlink($dirname);

        $this->copyOriginal($dirname,$new);

        if($deleteSource === true)
        $new->unlink();

        $this->commonMakeVersion($index);

        return;
    }


    // export
    // retourne la valeur pour l'exportation de cellules media/medias
    final public function export(?array $option=null):array
    {
        return $this->exportCommon($this->all(false)->pair('pathToUri'),$option);
    }


    // delete
    // sur effacement de la ligne
    final public function delete(?array $option=null):Orm\Cell
    {
        $this->unlinks(null,$option);

        return $this;
    }


    // rootPath
    // retourne le chemin racine pour le storage de média, ceci est dans colonne
    // possible de désactiver le remplacement de shortcut
    final public function rootPath(bool $shortcut=true):string
    {
        return $this->col()->rootPath($shortcut);
    }


    // tablePath
    // retourne le chemin racine pour le storage de média avec la table, ceci est dans colonne
    // possible de désactiver le remplacement de shortcut
    final public function tablePath(bool $shortcut=true):string
    {
        return $this->col()->tablePath($shortcut);
    }


    // hasVersion
    // retourne vrai si la colonne media a des versions
    final public function hasVersion():bool
    {
        return $this->col()->hasVersion();
    }


    // hasIndex
    // retourne vrai si le champ a plusieurs index
    final public function hasIndex():bool
    {
        return $this->col()->hasIndex();
    }


    // copyOriginal
    // copie l'original au chemin target
    final protected function copyOriginal(string $dirname,Main\File $file):void
    {
        $basename = $file->mimeBasename($file->getAttr('uploadBasename'));
        $basename = Base\Path::safeBasename($basename);
        $target = Base\Path::append($dirname,$basename);

        if($file instanceof Core\File\ImageRaster)
        {
            $filename = Base\Path::filename($target);
            $option = ['autoRotate'=>true];
            $file->compress($dirname,$filename,$option);
        }

        else
        $this->commonCopy($file,$target,$dirname);

        return;
    }


    // unlinkAll
    // efface tous les fichiers liés à la cellule
    // possible de fournir un objet files en premier argument (utilisé pour les committed callback)
    // utilise unlinkWhileEmpty pour effacer le maximum de dossier vide dans la hiérarchie
    // retourne le nombre de fichiers effacés
    final public function unlinks(?Main\Files $all=null,?array $option=null):int
    {
        $return = 0;
        $option = Base\Arr::plus(['unsetWhileEmpty'=>true,'com'=>false],$option);
        $unsetWhileEmpty = $this->unsetWhileEmptyAmount();
        $all = $all ?? $this->all();

        if(!empty($all) && $all->isNotEmpty())
        {
            foreach ($all as $key => $file)
            {
                $path = $file->path();
                $dirname = $file->dirname();
                $unlink = $file->unlink();

                if($unlink === true)
                $return++;

                if($option['unsetWhileEmpty'] === true && is_int($unsetWhileEmpty))
                Base\Dir::unlinkWhileEmpty($dirname,$unsetWhileEmpty);
            }

            if($option['com'] === true && $return > 0)
            {
                $text = static::langPlural($return,'com/pos/media/delete',['count'=>$return]);
                $this->com($text,'pos');
            }
        }

        return $return;
    }


    // unsetWhileEmptyAmount
    // retourne le nombre de niveaux de directoires à tenter de supprimer
    final public function unsetWhileEmptyAMount():int
    {
        $return = 2;
        $return += ($this->hasVersion())? 1:0;
        $return += ($this->hasIndex())? 1:0;

        return $return;
    }
    
    
    // pathExists
    // méthode protégé utilisé pour vérifier si un chemin existe
    protected static function pathExists($value):bool
    {
        return (is_string($value) && !empty($value) && Base\File::is($value));
    }
}

// init
Files::__init();
?>
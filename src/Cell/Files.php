<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
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
    protected static array $config = [];


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
        return $this->table()->hasPermission('mediaDelete') && $this->fileExists($index);
    }


    // checkCanBeDeleted
    // retourne vrai si le média peut être effacé, sinon envoie une exception
    final public function checkCanBeDeleted(?int $index=null):bool
    {
        return $this->canBeDeleted($index) ?: static::throw('cannotDelete',$index);
    }


    // canBeRegenerated
    // retourne vrai si le média peut être regénéré
    // doit avoir des versions
    final public function canBeRegenerated(?int $index=null):bool
    {
        return $this->hasVersion() && $this->table()->hasPermission('mediaRegenerate') && $this->fileExists($index);
    }


    // checkCanBeRegenerated
    // retourne vrai si le média peut être effacé, sinon envoie une exception
    final public function checkCanBeRegenerated(?int $index=null):bool
    {
        return $this->canBeRegenerated($index) ?: static::throw('cannotRegenerate',$index);
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
            $fromPath = $this->getAttr('fromPath');
            $return = Main\File::new($path,['fromPath'=>$fromPath]);
            $cellName = $this->row()->cellName();
            $return->setAttr('defaultAlt',$cellName);
        }

        return $return;
    }


    // commonCheckFile
    // retourne l'objet fichier, envoie une exception si non existant
    final protected function commonCheckFile(?int $index=null,$version=null):Main\File
    {
        return $this->commonFile($index,$version) ?: $this->commonThrow($index,$version);
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

        return $return ?: $col->defaultConvertExtension();
    }


    // commonVersion
    // retourne un objet files avec toutes les versions pour un index
    // retourne null si pas de version
    final protected function commonVersion(?int $index=null):?Main\Files
    {
        $return = null;

        if($this->isNotEmpty() && $this->hasVersion())
        {
            $fromPath = $this->getAttr('fromPath');
            $option = ['fromPath'=>$fromPath];
            $return = Main\Files::newOverload();
            $col = $this->col();

            foreach ($col->versions() as $key => $value)
            {
                $path = $this->commonFilePath($index,$key);

                if(Base\File::is($path))
                $return->set($key,$path,$option);
            }
        }

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
                $isImage = ($file instanceof Main\File\Image);

                $indexDirname = $this->commonBasePath($index,false);
                $originalDirname = $this->commonBasePath($index,null);

                if(empty($indexDirname) || empty($originalDirname))
                static::throw();

                foreach (Base\Dir::get($indexDirname) as $path)
                {
                    if($path !== $originalDirname && Base\Dir::emptyAndUnlink($path) !== true)
                    static::throw('cannotUnlink',$path);
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

            $count = count($return);
            if($option['com'] === true && $count > 0)
            {
                $lang = static::lang();
                $text = $lang->plural($count,'com/pos/media/regenerate',['count'=>$count]);
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
        if(!Base\Dir::setOrWritable($dirname))
        static::throw('cannnotCreateFolderStructure');

        $file->copy($path);

        return Base\File::is($path);
    }


    // commonProcess
    // lance le process de déplacement du média lié
    // efface le dossier de l'original avant de le recréer
    final protected function commonProcess(?int $index,Main\File $new):void
    {
        $this->col()->checkWritable();
        $dirname = $this->commonBasePath($index);
        $deleteSource = $new->getAttr('uploadDeleteSource');

        if(Base\Dir::is($dirname))
        Base\Dir::emptyAndUnlink($dirname);

        $this->copyOriginal($dirname,$new);

        if($deleteSource === true && !$new->isPhpTemp())
        $new->unlink();

        $this->commonMakeVersion($index);
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
        $all ??= $this->all();

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
                $lang = static::lang();
                $text = $lang->plural($return,'com/pos/media/delete',['count'=>$return]);
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


    // getVersionDefault
    // retourne une version par défaut pour un type
    final public function getVersionDefault(string $type)
    {
        return $this->getAttr(['versionDefault',$type]) ?? -1;
    }


    // getFirstImage
    // retourne le premier fichier image de la cellule
    final public function getFirstImage($version=null,?string $type=null):?Main\File
    {
        $return = null;

        if($this->hasVersion() && $version === null && $type !== null)
        $version = $this->getVersionDefault($type);

        $file = $this->getFirstFile($version);

        if($file instanceof Main\File\Image)
        $return = $file;

        return $return;
    }


    // pathExists
    // méthode protégé utilisé pour vérifier si un chemin existe
    protected static function pathExists($value):bool
    {
        return is_string($value) && !empty($value) && Base\File::is($value);
    }
}

// init
Files::__init();
?>
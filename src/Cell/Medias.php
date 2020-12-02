<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cell;
use Quid\Main;

// medias
// class to manage a cell containing a value which is a link to many files
class Medias extends FilesAlias
{
    // config
    protected static array $config = [];


    // cast
    // cast la cellule, retourne le path http ou base64
    final public function _cast():?string
    {
        return $this->commonCast();
    }


    // pair
    // si args est vide, retourne un objet files avec toues les fichiers d'une version
    // sinon envoie dans commonFile
    final public function pair($value=null,...$args)
    {
        $return = $this;

        if($value !== null || !empty($args))
        {
            $value = ($value === true)? null:$value;

            if(empty($args) && !is_int($value))
            $return = $this->indexes($value);

            else
            $return = $this->commonFile($value,...$args);
        }

        return $return;
    }


    // canBeDeleted
    // retourne vrai si le média peut être effacé
    // si média est requis, il doit au moins y avoir un
    final public function canBeDeleted(?int $index=null):bool
    {
        $return = parent::canBeDeleted($index);

        if($return === true && $this->isRequired())
        {
            $indexes = $this->indexes();
            if($indexes->count() <= 1)
            $return = false;
        }

        return $return;
    }


    // basename
    // retourne un basename de fichier dans la cellule
    final public function basename(?int $index=null,$version=null):?string
    {
        return $this->commonBasename($index,$version);
    }


    // cellPath
    // retourne le chemin de la cellule qui combine le nom de la table, le id de la ligne, le nom de la colonne et l'index
    final public function cellPath($index=null,$version=null):string
    {
        return $this->commonCellPath($index,$version);
    }


    // cellPathBasename
    // retourne le chemin de la cellule avec le basename et l'index, le fichier n'a pas besoin d'exister
    final public function cellPathBasename(?int $index=null,$version=null):?string
    {
        return $this->commonCellPathBasename($index,$version);
    }


    // basePath
    // retourne le base path à l'index qui est une combinaison du rootPath et cellPath
    final public function basePath($index=null,$version=null):string
    {
        return $this->commonBasePath($index,$version);
    }


    // filePath
    // retourne le file path à l'index qui combine le basePath et le basename qui est la valeur de la cellule
    final public function filePath(?int $index=null,$version=null):?string
    {
        return $this->commonFilePath($index,$version);
    }


    // fileExists
    // retourne vrai si le fichier à l'index existe
    final public function fileExists(?int $index=null,$version=null):bool
    {
        return $this->commonFileExists($index,$version);
    }


    // checkFileExists
    // envoie une exception si le fichier à l'index n'existe pas
    final public function checkFileExists(?int $index=null,$version=null):self
    {
        return $this->commonCheckFileExists($index,$version);
    }


    // file
    // retourne l'objet fichier, peut retourner null
    final public function file(?int $index=null,$version=null):?Main\File
    {
        return $this->commonFile($index,$version);
    }


    // checkFile
    // retourne l'objet fichier, envoie une exception si non existant
    final public function checkFile(?int $index=null,$version=null):Main\File
    {
        return $this->commonCheckFile($index,$version);
    }


    // versionExtension
    // retourne l'extension a utilisé pour la version à l'index donné
    final public function versionExtension(?int $index=null,$version=null,bool $exception=true):string
    {
        return $this->commonVersionExtension($index,$version,$exception);
    }


    // version
    // retourne un objet files avec toutes les versions pour un index, retourne null si pas de version
    final public function version(int $index):?Main\Files
    {
        return $this->commonVersion($index);
    }


    // makeVersion
    // reconstruit les versions pour l'index d'une image à partir de la configuration de la colonne
    final public function makeVersion($index=null,?array $option=null):array
    {
        return $this->commonMakeVersion($index,$option);
    }


    // getFirstFile
    // retourne le premier fichier pour la cellule
    // possible de spécifier une version
    final public function getFirstFile($version=-1):?Main\File
    {
        $args = [0];
        if($this->hasVersion())
        $args[] = $version;

        return $this->file(...$args);
    }


    // process
    // lance le process de déplacement des médias lié
    final public function process(Main\Files $olds,Main\Files $news,array $regenerate,?array $option=null):void
    {
        $this->unlinks($olds,$option);

        foreach ($news as $index => $new)
        {
            $this->commonProcess($index,$new);
        }

        if(!empty($regenerate))
        $this->makeVersion($regenerate,$option);
    }


    // checkIndex
    // envoie une exception si l'index est invalid, sinon retourne l'index
    final protected function checkIndex(?int $index=null):int
    {
        $return = null;
        $amount = $this->col()->getAmount();

        if($index === null)
        $index = 0;

        if(is_int($index) && is_int($amount))
        {
            if($index >= 0 && $index < $amount)
            $return = $index;
        }

        if(!is_int($return))
        static::throw('invalidIndex',$this->name(),$index);

        return $return;
    }


    // indexes
    // retourne un objet files avec tous les index de l'objet
    // possible de spécifier une version
    final public function indexes($version=null):Main\Files
    {
        $return = Main\Files::newOverload();
        $indexes = $this->indexesExists($version);

        foreach ($indexes as $index)
        {
            $return->set($index,$this->checkFile($index,$version));
        }

        return $return;
    }


    // indexesExists
    // retourne un tableau avec tous les indexes qui existent
    final public function indexesExists($version=null):array
    {
        $return = [];
        $get = $this->get();

        if(is_array($get) && !empty($get))
        {
            foreach ($get as $index => $basename)
            {
                if($this->fileExists($index,$version))
                $return[] = $index;
            }
        }

        return $return;
    }


    // all
    // retourne un objet files avec toutes les index, les versions et l'original
    // possible d'exclure les versions
    final public function all(bool $addVersion=true):Main\Files
    {
        $return = Main\Files::newOverload();
        $indexes = $this->indexes();

        foreach ($indexes as $index => $file)
        {
            $return->set($index,$file);

            if($addVersion === true)
            {
                $version = $this->version($index);

                if(!empty($version))
                {
                    foreach ($version as $key => $file2)
                    {
                        $k = $index.'/'.$key;
                        $return->set($k,$file2);
                    }
                }
            }
        }

        return $return;
    }
}

// init
Medias::__init();
?>
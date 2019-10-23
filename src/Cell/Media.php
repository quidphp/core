<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cell;
use Quid\Base;
use Quid\Core;
use Quid\Main;

// media
// class to work with a cell containing a value which is a link to a file
class Media extends FilesAlias
{
    // config
    public static $config = [];


    // cast
    // cast la cellule, retourne le path http ou base64
    public function _cast():?string
    {
        return $this->commonCast();
    }


    // pair
    // si value est true, retourne file
    public function pair($value=null,...$args)
    {
        return $this->commonPair($value,...$args);
    }


    // canBeDeleted
    // retourne vrai si le média peut être effacé
    // si média est requis, ne peut pas effacé
    public function canBeDeleted(?int $index=null):bool
    {
        return (parent::canBeDeleted($index) && !$this->isRequired())? true:false;
    }


    // basename
    // retourne le basename du fichier dans la cellule
    public function basename($version=null):?string
    {
        return $this->commonBasename(null,$version);
    }


    // cellPath
    // retourne le chemin de la cellule qui combine le nom de la table, le id de la ligne et le nom de la colonne
    public function cellPath($version=null):string
    {
        return $this->commonCellPath(null,$version);
    }


    // cellPathBasename
    // retourne le chemin de la cellule avec le basename
    // le fichier n'a pas besoin d'exister
    public function cellPathBasename($version=null):?string
    {
        return $this->commonCellPathBasename(null,$version);
    }


    // basePath
    // retourne le base path qui est une combinaison du rootPath et cellPath
    public function basePath($version=null):string
    {
        return $this->commonBasePath(null,$version);
    }


    // filePath
    // retourne le file path qui combine le basePath et le basename qui est la valeur de la cellule
    public function filePath($version=null):?string
    {
        return $this->commonFilePath(null,$version);
    }


    // fileExists
    // retourne vrai si le fichier existe
    public function fileExists($version=null):bool
    {
        return $this->commonFileExists(null,$version);
    }


    // checkFileExists
    // envoie une exception si le fichier n'existe pas
    public function checkFileExists($version=null):self
    {
        return $this->commonCheckFileExists(null,$version);
    }


    // file
    // retourne l'objet fichier
    public function file($version=null):?Main\File
    {
        return $this->commonFile(null,$version);
    }


    // checkFile
    // retourne l'objet fichier, envoie une exception si non existant
    public function checkFile($version=null):Main\File
    {
        return $this->commonCheckFile(null,$version);
    }


    // versionExtension
    // retourne l'extension a utilisé pour la version
    public function versionExtension($version=null,bool $exception=true):string
    {
        return $this->commonVersionExtension(null,$version,$exception);
    }


    // version
    // retourne un objet files avec toutes les versions, retourne null si pas de version
    public function version():?Main\Files
    {
        return $this->commonVersion();
    }


    // makeVersion
    // reconstruit les versions pour une image à partir de la configuration de la colonne
    public function makeVersion(?array $option=null):array
    {
        return $this->commonMakeVersion(null,$option);
    }


    // generalOutput
    // génère le output pour général
    public function generalOutput(?array $option=null):string
    {
        return $this->commonGeneralOutput(null,$option);
    }


    // downloadRoute
    // retourne la route pour le téléchargement
    public function downloadRoute():Core\Route
    {
        return $this->commonDownloadRoute();
    }


    // process
    // lance le process de déplacement du média lié
    public function process(Main\Files $olds,?Main\File $new=null,bool $regenerate=false,?array $option=null):void
    {
        $this->unlinks($olds,$option);

        if(!empty($new))
        $this->commonProcess(null,$new);

        elseif($regenerate === true)
        $this->makeVersion($option);

        return;
    }


    // all
    // retourne un objet files avec toutes les versions et l'original, possible d'exclure les versions
    public function all(bool $addVersion=true):Main\Files
    {
        $return = Main\Files::newOverload();
        $path = $this->filePath();

        if(is_string($path) && Base\File::is($path))
        $return->set(0,$path);

        if($addVersion === true)
        {
            $version = $this->version();

            if(!empty($version))
            $return->add($version);
        }

        return $return;
    }
}

// init
Media::__init();
?>
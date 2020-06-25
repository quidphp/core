<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Service;
use Quid\Main;

// scssPhp
// class that grants methods to use scssphp/scssphp for compiling scss files
class ScssPhp extends Main\ServiceAlias
{
    // config
    protected static array $config = [
        'formatsPossible'=>[
            'normal'=>\ScssPhp\ScssPhp\Formatter\Expanded::class, // si compress est false
            'compress'=>\ScssPhp\ScssPhp\Formatter\Crunched::class], // si compress est true
        'compress'=>true, // permet de spécifier s'il faut compresser ou non le rendu
        'format'=>null, // permet de spécifier un format, ne prend pas en compte l'option compress
        'importPaths'=>null, // chemins d'importation à déclarer
        'variables'=>null // variables à inclure dans la compilation
    ];


    // dynamique
    protected \ScssPhp\ScssPhp\Compiler $compiler; // conserve une copie de l'objet compiler


    // construct
    // construit le service et lit l'objet scssPhp
    final public function __construct(?array $attr=null)
    {
        parent::__construct($attr);
        $this->compiler = new \ScssPhp\ScssPhp\Compiler();
    }


    // getCompiler
    // retourne l'objet compiler
    final public function getCompiler():\ScssPhp\ScssPhp\Compiler
    {
        return $this->compiler;
    }


    // getFormat
    // retourne le format à utiliser
    final public function getFormat():string
    {
        $return = $this->getAttr('format');

        if(empty($return))
        {
            $formats = $this->getAttr('formatsPossible');
            $compress = $this->getAttr('compress');
            $return = ($compress === true)? $formats['compress']:$formats['normal'];
        }

        return $return;
    }


    // getImportPaths
    // retourne les chemins d'importation à déclarer
    final public function getImportPaths():array
    {
        return (array) $this->getAttr('importPaths');
    }


    // getVariables
    // retourne les variables à inclure dans la compilation
    final public function getVariables():array
    {
        return (array) $this->getAttr('variables');
    }


    // trigger
    // permet de faire un rendu scss à partir d'une string ou objet file\css fourni en argument
    // possible de fournir des variables à déclarer avant le chargement du script
    // retourne la string css
    final public function trigger($value):string
    {
        $return = null;
        $compiler = $this->getCompiler();
        $format = $this->getFormat();
        $importPaths = $this->getImportPaths();
        $variables = $this->getVariables();

        if($value instanceof Main\File\Css)
        {
            $dirname = $value->dirname();
            if(!in_array($dirname,$importPaths,true))
            $importPaths[] = $dirname;

            $value = $value->read(true,true);
        }

        if(is_string($value))
        {
            $compiler->setFormatter($format);

            if(!empty($importPaths))
            $compiler->setImportPaths($importPaths);

            if(!empty($variables))
            $compiler->setVariables($variables);

            $return = $compiler->compile($value);
        }

        else
        static::throw('invalidValue');

        return $return;
    }


    // staticTrigger
    // méthode statique pour créer l'objet lancer la compilation
    // retourne une string
    final public static function staticTrigger(string $value,?array $attr=null):string
    {
        $return = null;
        $minifier = new static($attr);
        $return = $minifier->trigger($value);

        return $return;
    }
}

// init
ScssPhp::__init();
?>
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Service;
use Quid\Core;
use Quid\Main;

// scssPhp
// class that grants methods to use scssphp/scssphp for compiling scss files
class ScssPhp extends Main\ServiceAlias
{
    // config
    public static $config = [
        'formatsPossible'=>[
            \ScssPhp\ScssPhp\Formatter\Expanded::class, // si compress est false
            \ScssPhp\ScssPhp\Formatter\Crunched::class], // si compress est true
        'compress'=>true, // permet de spécifier s'il faut compresser ou non le rendu
        'format'=>null // permet de spécifier un format, ne prend pas en compte l'option compress
    ];


    // dynamique
    protected $compiler = null; // conserve une copie de l'objet compiler


    // construct
    // construit le service et lit l'objet scssPhp
    final public function __construct(string $key,?array $attr=null)
    {
        parent::__construct($key,$attr);
        $this->compiler = new \ScssPhp\ScssPhp\Compiler();

        return;
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

            if(is_array($formats))
            {
                $return = $formats[0];

                $compress = $this->getAttr('compress');
                if($compress === true)
                $return = $formats[1];
            }
        }
        
        return $return;
    }


    // trigger
    // permet de faire un rendu scss à partir d'une string ou objet file\css fourni en argument
    // possible de fournir des variables à déclarer avant le chargement du script
    // retourne la string css
    final public function trigger($value,?array $importPaths=null,?array $variables=null):string
    {
        $return = null;
        $compiler = $this->getCompiler();
        $format = $this->getFormat();
        $importPaths = (array) $importPaths;
        
        if($value instanceof Core\File\Css)
        {
            $importPaths[] = $value->dirname();
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
}

// init
ScssPhp::__init();
?>
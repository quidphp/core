<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\File;
use Quid\Base;
use Quid\Core;
use Quid\Main;

// css
// class for a css or scss file
class Css extends Main\File\Css
{
    // config
    public static $config = [
        'service'=>Core\Service\ScssPhp::class,
        'extension'=>['css','scss']
    ];


    // compile
    // compile le fichier scss ou css en utilisant le service
    // retourne le css compilé
    final public function compile(?array $importPaths=null,?array $variables=null,?array $option=null):?string
    {
        $return = null;
        $scssPhp = $this->getServiceObj($option);
        $return = $scssPhp->trigger($this,$importPaths,$variables);

        return $return;
    }


    // compileFrom
    // permet de compiler dans le fichier à partir d'un ou plusieurs fichiers scss
    final public function compileFrom($values,?array $importPaths=null,?array $variables=null,int $min=0,?array $option=null):self
    {
        $option = Base\Arr::plus(['extension'=>$this->getAttr('extension')],$option);
        $concatenator = Main\Concatenator::newOverload();
        $scssPhp = static::getServiceObj($option);
        $importPaths = (array) $importPaths;

        if(!is_array($values))
        $values = (array) $values;
        ksort($values);
        
        foreach ($values as $key => $value)
        {
            if(!empty($value))
            {
                if(!is_string($value) || Base\Finder::is($value))
                {
                    if(is_string($value) && Base\Dir::is($value))
                    $dirname = $value;

                    else
                    {
                        $value = static::new($value);
                        $dirname = $value->dirname();
                    }

                    if($key >= $min && !in_array($dirname,$importPaths,true))
                    $importPaths[] = $dirname;

                    $concatenator->add($value,$option);
                }
            }
        }

        $scss = $concatenator->trigger($this);
        $css = $scssPhp->trigger($scss,$importPaths,$variables);
        $this->overwrite($css);

        return $this;
    }


    // getServiceClass
    // retourne la classe du service
    final public function getServiceClass():string
    {
        return $this->getAttr('service')::getOverloadClass();
    }


    // getServiceObj
    // retourne l'objet du service
    final public function getServiceObj(?array $option=null):Main\Service
    {
        $service = $this->getServiceClass();
        $return = new $service(__METHOD__,$option);

        return $return;
    }


    // compileMany
    // permet de compiler un ou plusieurs fichiers css/scss
    final public static function compileMany(array $value,?array $option=null):Main\Files
    {
        $return = Main\Files::newOverload();
        $variables = static::getScssVariables();

        foreach ($value as $to => $from)
        {
            if(is_string($to) && !empty($to) && !empty($from))
            {
                $fromDir = Base\Dir::getDirFromFileAndDir($from);
                if(Base\Dir::isOlderThanFrom($to,$fromDir,true,['visible'=>true,'extension'=>['css','scss']]))
                {
                    $to = Main\File::newCreate($to);

                    if($to instanceof self)
                    $to->compileFrom($from,null,$variables,10,$option);

                    $return->add($to);
                }
            }
        }

        return $return;
    }


    // getScssVariables
    // génère un tableau de variable à injecter dans la feuille de style scss
    final public static function getScssVariables():array
    {
        $return = [];

        foreach (Base\Finder::allShortcuts() as $key => $value)
        {
            if(!Base\Lang::is($value))
            $value = Base\Finder::normalize($value);
            $key = 'finder'.ucfirst($key);

            $return[$key] = $value;
        }

        foreach (Base\Uri::allShortcuts() as $key => $value)
        {
            if(!Base\Lang::is($value))
            $value = Base\Uri::output($value);
            $key = 'uri'.ucfirst($key);

            $return[$key] = $value;
        }

        return $return;
    }
}

// init
Css::__init();
?>
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;
use Quid\Core;
use Quid\Main;

// css
// class for a css or scss file
class Css extends TextAlias
{
    // config
    public static $config = [
        'group'=>'css',
        'service'=>Core\Service\ScssPhp::class
    ];


    // compile
    // compile le fichier scss ou css en utilisant le service
    // retourne le css compilé
    public function compile(?array $importPaths=null,?array $variables=null,?array $option=null):?string
    {
        $return = null;
        $scssPhp = static::getServiceObj($option);
        $return = $scssPhp->trigger($this,$importPaths,$variables);

        return $return;
    }


    // compileFrom
    // permet de compiler dans le fichier à partir d'un ou plusieurs fichiers scss
    public function compileFrom($values,?array $importPaths=null,?array $variables=null,int $min=0,?array $option=null):self
    {
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
                $value = static::new($value);
                $dirname = $value->dirname();

                if($key >= $min && !in_array($dirname,$importPaths,true))
                $importPaths[] = $dirname;

                $concatenator->add($value,$option);
            }
        }

        $scss = $concatenator->trigger($this);
        $css = $scssPhp->trigger($scss,$importPaths,$variables);
        $this->overwrite($css);

        return $this;
    }


    // getServiceClass
    // retourne la classe du service
    public static function getServiceClass():string
    {
        return static::$config['service']::getOverloadClass();
    }


    // getServiceObj
    // retourne l'objet du service
    public static function getServiceObj(?array $option=null):Main\Service
    {
        $service = static::getServiceClass();
        $return = new $service(__METHOD__,$option);

        return $return;
    }
}

// config
Css::__config();
?>
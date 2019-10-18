<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Service;
use Composer\Autoload;
use Quid\Base;
use Quid\Core;

// composer
// class that grants some methods related to the composer autoloader
class Composer extends Core\ServiceAlias
{
    // config
    public static $config = [];


    // construct
    // constructeur privé
    private function __construct()
    {
        return;
    }


    // get
    // retourne l'objet composer à partir du pool de callable autoload
    public static function get():Autoload\ClassLoader
    {
        $return = null;

        foreach (Base\Autoload::all() as $key => $value)
        {
            if(is_array($value) && !empty($value[0]) && $value[0] instanceof Autoload\ClassLoader)
            {
                $return = $value[0];
                break;
            }
        }

        return $return;
    }


    // getPsr4
    // retourne un tableau avec tous les psr4 de composer
    public static function getPsr4():array
    {
        $return = [];
        $composer = static::get();
        $psr4 = $composer->getPrefixesPsr4();
        
        if(!empty($psr4))
        {
            foreach ($psr4 as $namespace => $path)
            {
                if(is_string($namespace) && is_array($path) && !empty($path))
                {
                    $v = current($path);
                    $namespace = rtrim($namespace,'\\');
                    $return[$namespace] = $v;
                }
            }
        }

        return $return;
    }


    // setPsr4
    // enregistre les psr4 de composer dans base/autoload
    public static function setPsr4():void
    {
        $psr4 = static::getPsr4();
        Base\Autoload::setsPsr4($psr4);

        return;
    }


    // setClassMapAuthoritative
    // active ou désactive la fonctionnalitée class map authoritative de composer
    public static function setClassMapAuthoritative(bool $value):void
    {
        $composer = static::get();
        $composer->setClassMapAuthoritative($value);
        
        return;
    }
}

// init
Composer::__init();
?>
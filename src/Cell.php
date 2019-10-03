<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Base;
use Quid\Orm;

// cell
// extended class to represent an existing cell within a row
class Cell extends Orm\Cell
{
    // trait
    use _routeAttr;
    use _accessAlias;


    // config
    public static $config = [];


    // getOverloadKeyPrepend
    // retourne le prepend de la clé à utiliser pour le tableau overload
    public static function getOverloadKeyPrepend():?string
    {
        return (static::class !== self::class && !Base\Fqcn::sameName(static::class,self::class))? 'Cell':null;
    }
}

// init
Cell::__init();
?>
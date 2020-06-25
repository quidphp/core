<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core;
use Quid\Base;
use Quid\Main;

// error
// extended class used as an error handler
class Error extends Main\Error
{
    // config
    protected static array $config = [
        'logClass'=>[Row\LogError::class,Main\File\Error::class], // classe pour log, , s'il y a en plusieurs utilise seulement le premier qui fonctionne
        'type'=>[ // description des types additionneles à boot
            33=>['key'=>'dbException','name'=>'Database Exception'],
            34=>['key'=>'dbException','name'=>'Catchable database exception'],
            35=>['key'=>'routeException','name'=>'Route Exception'],
            36=>['key'=>'routeBreakException','name'=>'Route break exception']]
    ];


    // init
    // initialise la prise en charge des erreurs, exception et assertion
    final public static function init():void
    {
        parent::init();
        Base\Error::setHandler([static::class,'handler']);
        Base\Exception::setHandler([static::class,'exception']);
        Base\Assert::setHandler([static::class,'assert']);
    }
}

// init
Error::__init();
?>
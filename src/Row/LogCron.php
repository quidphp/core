<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Row;
use Quid\Core;
use Quid\Main;

// logCron
// class to represent a row of the logCron table, stores cron jobs results
class LogCron extends Core\RowAlias implements Main\Contract\Log
{
    // trait
    use _log;


    // config
    protected static array $config = [
        'priority'=>1005,
        'cols'=>[
            'route'=>['required'=>true,'general'=>true],
            'json'=>['required'=>true]]
    ];


    // newData
    // crée le tableau d'insertion
    final public static function newData(Core\Route $route,array $data):array
    {
        $return = [];
        $return['route'] = $route::classFqcn();
        $return['json'] = $data;

        return $return;
    }
}

// init
LogCron::__init();
?>
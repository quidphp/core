<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
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
        'panel'=>false,
        'parent'=>'system',
        'cols'=>[
            'context'=>['class'=>Core\Col\Context::class],
            'route'=>['required'=>true,'general'=>true],
            'json'=>['required'=>true]]
    ];


    // prepareLogData
    // crée le tableau d'insertion
    final protected static function prepareLogData(Core\Route $route,array $data):array
    {
        return [
            'route'=>$route::classFqcn(),
            'json'=>$data
        ];
    }
}

// init
LogCron::__init();
?>
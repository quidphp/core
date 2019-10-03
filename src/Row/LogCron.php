<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
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
    public static $config = [
        'panel'=>false,
        'search'=>false,
        'parent'=>'system',
        'priority'=>1005,
        'cols'=>[
            'context'=>['class'=>Core\Col\Context::class],
            'type'=>['general'=>true,'relation'=>'logCronType'],
            'json'=>['class'=>Core\Col\JsonExport::class]],
        'deleteTrim'=>500 // custom
    ];


    // newData
    // crée le tableau d'insertion
    public static function newData():array
    {
        return [];
    }
}

// init
LogCron::__init();
?>
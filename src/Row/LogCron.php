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
            'route'=>['required'=>true,'general'=>true],
            'context'=>['class'=>Core\Col\Context::class],
            'json'=>['class'=>Core\Col\JsonExport::class,'required'=>true]],
        'permission'=>array(
            'nobody'=>array('insert'=>true),
            'shared'=>array('insert'=>true),
            'user'=>array('insert'=>true),
            'contributor'=>array('insert'=>true,'update'=>false,'delete'=>false),
            'editor'=>array('insert'=>true,'update'=>false,'delete'=>false),
            'subAmin'=>array('update'=>false),
            'admin'=>array('update'=>false)),
        'deleteTrim'=>500 // custom
    ];


    // newData
    // crée le tableau d'insertion
    public static function newData(Core\Route $route,array $data):array
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
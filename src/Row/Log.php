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

// log
// class to represent a row of the log table, stores user activities
class Log extends Core\RowAlias implements Main\Contract\Log
{
    // trait
    use _log;


    // config
    protected static array $config = [
        'priority'=>1000,
        'panel'=>false,
        'parent'=>'system',
        'permission'=>[
            '*'=>['insert'=>true],
            'nobody'=>['insert'=>true],
            'admin'=>['update'=>false]],
        'cols'=>[
            'context'=>['class'=>Core\Col\Context::class],
            'request'=>['class'=>Core\Col\Request::class],
            'type'=>['general'=>true,'relation'=>'logType']],
        'type'=>[ // type de log
            1=>'login',
            2=>'logout',
            3=>'resetPassword',
            4=>'activatePassword',
            5=>'changePassword',
            6=>'register',
            7=>'form']
    ];


    // getTypeCode
    // retourne le code à partir du type
    final public static function getTypeCode(string $type):int
    {
        return (in_array($type,static::$config['type'],true))? array_search($type,static::$config['type'],true):0;
    }


    // prepareLogData
    // crée le tableau d'insertion
    final protected static function prepareLogData(string $type,array $json):array
    {
        return ['type'=>static::getTypeCode($type),'json'=>$json];
    }
}

// init
Log::__init();
?>
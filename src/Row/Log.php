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

// log
// class to represent a row of the log table, stores user activities
class Log extends Core\RowAlias implements Main\Contract\Log
{
    // trait
    use _log;


    // config
    public static $config = [
        'panel'=>false,
        'search'=>false,
        'priority'=>1000,
        'parent'=>'system',
        'cols'=>[
            'context'=>['class'=>Core\Col\Context::class],
            'request'=>['class'=>Core\Col\Request::class],
            'type'=>['general'=>true,'relation'=>'logType']],
        'permission'=>[
            'nobody'=>['insert'=>true],
            'admin'=>['update'=>false]],
        'deleteTrim'=>500, // custom
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


    // newData
    // crée le tableau d'insertion
    final public static function newData(string $type,array $json):array
    {
        return ['type'=>static::getTypeCode($type),'json'=>$json];
    }
}

// init
Log::__init();
?>
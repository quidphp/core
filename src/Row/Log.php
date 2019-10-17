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
            'type'=>['general'=>true,'relation'=>'logType'],
            'json'=>['class'=>Core\Col\JsonExport::class]],
        'permission'=>array(
            'nobody'=>array('insert'=>true),
            'shared'=>array('insert'=>true),
            'user'=>array('insert'=>true),
            'contributor'=>array('insert'=>true,'update'=>false,'delete'=>false),
            'editor'=>array('insert'=>true,'update'=>false,'delete'=>false),
            'subAmin'=>array('update'=>false),
            'admin'=>array('update'=>false)),
        'deleteTrim'=>500, // custom
        'type'=>[ // type de log
            1=>'login',
            2=>'logout',
            3=>'resetPassword',
            4=>'activatePassword',
            5=>'changePassword',
            6=>'register']
    ];


    // getTypeCode
    // retourne le code à partir du type
    public static function getTypeCode(string $type):int
    {
        return (in_array($type,static::$config['type'],true))? array_search($type,static::$config['type'],true):0;
    }


    // newData
    // crée le tableau d'insertion
    public static function newData(string $type,array $json):array
    {
        return ['type'=>static::getTypeCode($type),'json'=>$json];
    }
}

// init
Log::__init();
?>
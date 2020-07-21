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

// logSql
// class to represent a row of the logSql table, stores sql queries
class LogSql extends Core\RowAlias implements Main\Contract\Log
{
    // trait
    use _log;


    // config
    protected static array $config = [
        'priority'=>1003,
        'panel'=>false,
        'parent'=>'system',
        'cols'=>[
            'context'=>['class'=>Core\Col\Context::class],
            'request'=>['class'=>Core\Col\Request::class],
            'type'=>['general'=>true,'relation'=>'logSqlType']],
        'logSql'=>[
            'truncate'=>false],
        'type'=>[ // type de logSql
            1=>'select',
            2=>'show',
            3=>'insert',
            4=>'update',
            5=>'delete',
            6=>'create',
            7=>'alter',
            8=>'truncate',
            9=>'drop']
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
LogSql::__init();
?>
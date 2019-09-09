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
use Quid\Base;

// logSql
// class to represent a row of the logSql table, stores sql queries
class LogSql extends Core\RowAlias implements Main\Contract\Log
{
    // trait
    use _log;


    // config
    public static $config = [
        'panel'=>false,
        'search'=>false,
        'parent'=>'system',
        'priority'=>1003,
        'cols'=>[
            'context'=>['class'=>Core\Col\Context::class],
            'request'=>['class'=>Core\Col\Request::class],
            'type'=>['general'=>true,'relation'=>'logSqlType'],
            'json'=>['class'=>Core\Col\JsonExport::class]],
        'logSql'=>[
            'truncate'=>false],
        'deleteTrim'=>500, // custom
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
    public static function getTypeCode(string $type):int
    {
        return (in_array($type,static::$config['type'],true))? array_search($type,static::$config['type'],true):0;
    }


    // prepareJson
    // prépare le tableau, retourne une chaine json
    // efface les données si le tableau est trop long pour la colonne
    public static function prepareJson(array $value):string
    {
        $return = Base\Json::encode($value);
        $table = static::tableFromFqcn();
        $col = $table->col('json');

        if($col->validate($return) !== true)
        {
            $value = ['truncated'=>true];
            $return = Base\Json::encode($value);
        }

        return $return;
    }


    // newData
    // crée le tableau d'insertion
    public static function newData(string $type,array $json):array
    {
        return ['type'=>static::getTypeCode($type),'json'=>static::prepareJson($json)];
    }
}

// config
LogSql::__config();
?>
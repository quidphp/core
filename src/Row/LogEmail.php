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

// logEmail
// class to represent a row of the logEmail table, stores emails sent
class LogEmail extends Core\RowAlias implements Main\Contract\Log
{
    // trait
    use _log;


    // config
    protected static array $config = [
        'priority'=>1004,
        'panel'=>false,
        'parent'=>'system',
        'permission'=>[
            '*'=>['insert'=>true],
            'nobody'=>['insert'=>true],
            'admin'=>['update'=>false]],
        'cols'=>[
            'context'=>['class'=>Core\Col\Context::class],
            'request'=>['class'=>Core\Col\Request::class],
            'status'=>['class'=>Core\Col\Boolean::class]]
    ];


    // prepareLogData
    // crée le tableau d'insertion
    final protected static function prepareLogData(bool $status,array $json):array
    {
        return ['status'=>(int) $status,'json'=>$json];
    }
}

// init
LogEmail::__init();
?>
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

// logError
// class to represent a row of the logError table, stores error objects
class LogError extends Core\RowAlias implements Main\Contract\Log
{
    // trait
    use _log;


    // config
    protected static array $config = [
        'priority'=>1001,
        'panel'=>false,
        'parent'=>'system',
        'cols'=>[
            'context'=>['class'=>Core\Col\Context::class],
            'request'=>['class'=>Core\Col\Request::class],
            'type'=>['general'=>true,'relation'=>'error/label'],
            'error'=>['required'=>true,'class'=>Core\Col\Error::class]]
    ];


    // prepareLogData
    // crée le tableau d'insertion
    final protected static function prepareLogData(Core\Error $error):array
    {
        return [
            'type'=>$error->getCode(),
            'error'=>$error->toArray()
        ];
    }
}

// init
LogError::__init();
?>
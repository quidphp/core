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
        'permission'=>[
            '*'=>['insert'=>true],
            'nobody'=>['insert'=>true],
            'admin'=>['update'=>false]],
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
        $return = [];
        $return['type'] = $error->getCode();
        $return['error'] = $error->toArray();

        return $return;
    }
}

// init
LogError::__init();
?>
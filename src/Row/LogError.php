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

// logError
// class to represent a row of the logError table, stores error objects
class LogError extends Core\RowAlias implements Main\Contract\Log
{
    // trait
    use _log;


    // config
    public static $config = [
        'panel'=>false,
        'search'=>false,
        'parent'=>'system',
        'priority'=>1001,
        'cols'=>[
            'context'=>['class'=>Core\Col\Context::class],
            'request'=>['class'=>Core\Col\Request::class],
            'type'=>['general'=>true,'relation'=>'error/label'],
            'error'=>['required'=>true,'class'=>Core\Col\Error::class]],
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
    public static function newData(Core\Error $error):array
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
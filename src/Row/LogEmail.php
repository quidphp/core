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
    public static array $config = [
        'priority'=>1004,
        'cols'=>[
            'request'=>['class'=>Core\Col\Request::class],
            'status'=>['class'=>Core\Col\Boolean::class]]
    ];


    // newData
    // crée le tableau d'insertion
    final public static function newData(bool $status,array $json):array
    {
        return ['status'=>(int) $status,'json'=>$json];
    }
}

// init
LogEmail::__init();
?>
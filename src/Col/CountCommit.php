<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// countCommit
// class for the countCommit column, increments itself automatically on commit
class CountCommit extends Core\ColAlias
{
    // config
    public static $config = [
        'required'=>true,
        'check'=>['kind'=>'int']
    ];


    // onCommit
    // sur commit incrémente le count
    public function onCommit($value,array $row,?Core\Cell $cell=null,array $option):int
    {
        return (is_int($value))? ($value + 1):0;
    }
}

// config
CountCommit::__config();
?>
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Col;
use Quid\Core;

// countCommit
// class for the countCommit column, increments itself automatically on commit
class CountCommit extends Core\ColAlias
{
    // config
    protected static array $config = [
        'required'=>true,
        'check'=>['kind'=>'int']
    ];


    // onCommit
    // sur commit incrémente le count
    final protected function onCommit($value,?Core\Cell $cell=null,array $row,array $option):int
    {
        return (is_int($value))? ($value + 1):0;
    }
}

// init
CountCommit::__init();
?>
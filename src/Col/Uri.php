<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// uri
// class for a column managing an uri (relative or absolute)
class Uri extends Core\ColAlias
{
    // config
    public static $config = [
        'validate'=>[1=>'uri'],
        'check'=>['kind'=>'char']
    ];
}

// init
Uri::__init();
?>
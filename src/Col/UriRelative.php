<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// uriRelative
// class for a column managing a relative uri
class UriRelative extends Core\ColAlias
{
    // config
    public static $config = [
        'validate'=>[1=>'uriRelative'],
        'check'=>['kind'=>'char']
    ];
}

// init
UriRelative::__init();
?>
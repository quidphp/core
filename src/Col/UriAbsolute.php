<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// uriAbsolute
// class for a column managing an absolute uri
class UriAbsolute extends Core\ColAlias
{
    // config
    public static $config = [
        'validate'=>[1=>'uriAbsolute'],
        'check'=>['kind'=>'char']
    ];
}

// init
UriAbsolute::__init();
?>
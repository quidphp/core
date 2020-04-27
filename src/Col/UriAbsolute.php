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

// uriAbsolute
// class for a column managing an absolute uri
class UriAbsolute extends Core\ColAlias
{
    // config
    protected static array $config = [
        'validate'=>[1=>'uriAbsolute'],
        'check'=>['kind'=>'char']
    ];
}

// init
UriAbsolute::__init();
?>
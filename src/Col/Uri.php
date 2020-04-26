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

// uri
// class for a column managing an uri (relative or absolute)
class Uri extends Core\ColAlias
{
    // config
    public static array $config = [
        'validate'=>[1=>'uri'],
        'keyboard'=>'url',
        'check'=>['kind'=>'char']
    ];
}

// init
Uri::__init();
?>
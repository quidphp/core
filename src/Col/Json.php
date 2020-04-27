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
use Quid\Base;
use Quid\Core;

// json
// class for a column which manages json values
class Json extends Core\ColAlias
{
    // config
    protected static array $config = [
        'search'=>false,
        'onSet'=>[Base\Json::class,'onSet'],
        'onGet'=>[Base\Json::class,'onGet'],
        'check'=>['kind'=>'text']
    ];
}

// init
Json::__init();
?>
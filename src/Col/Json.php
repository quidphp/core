<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;
use Quid\Core;

// json
// class for a column which manages json values
class Json extends Core\ColAlias
{
    // config
    public static $config = [
        'search'=>false,
        'panel'=>'admin',
        'onSet'=>[Base\Json::class,'onSet'],
        'onGet'=>[Base\Json::class,'onGet'],
        'check'=>['kind'=>'text']
    ];
}

// config
Json::__config();
?>
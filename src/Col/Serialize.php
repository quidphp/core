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

// serialize
// class for a column which should serialize its value
class Serialize extends Core\ColAlias
{
    // config
    protected static array $config = [
        'search'=>false,
        'onSet'=>[Base\Crypt::class,'onSetSerialize'],
        'onGet'=>[Base\Crypt::class,'onGetSerialize'],
        'check'=>['kind'=>'text'],
        'onComplex'=>true
    ];
}

// init
Serialize::__init();
?>
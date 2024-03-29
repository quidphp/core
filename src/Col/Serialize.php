<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;
use Quid\Core;
use Quid\Orm;

// serialize
// class for a column which should serialize its value
class Serialize extends Core\ColAlias
{
    // config
    protected static array $config = [
        'search'=>false,
        'check'=>['kind'=>'text'],
        'onComplex'=>true
    ];


    // onGet
    // get la value à déserializer
    protected function onGet($return,?Orm\Cell $cell,array $option)
    {
        if(is_string($return))
        $return = Base\Crypt::unserialize($return);

        return $return;
    }


    // onSet
    // set la value, à serializer
    protected function onSet($return,?Orm\Cell $cell,array $row,array $option)
    {
        if(is_array($return) || is_object($return))
        $return = Base\Crypt::serialize($return);

        return $return;
    }
}

// init
Serialize::__init();
?>
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;
use Quid\Base;

// serialize
// class for a file with content that should be serialized
class Serialize extends TextAlias
{
    // config
    public static $config = [
        'group'=>null,
        'option'=>array(
            'read'=>[
                'callback'=>[Base\Crypt::class,'unserialize']],
            'write'=>[
                'callback'=>[Base\Crypt::class,'serialize']])
    ];
}

// init
Serialize::__init();
?>
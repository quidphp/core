<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;
use Quid\Main;

// cache
// class for a cache storage file
class Cache extends SerializeAlias implements Main\Contract\FileStorage
{
    // trait
    use Main\File\_storage;


    // config
    public static $config = [
        'dirname'=>'[storageCache]'
    ];
}

// init
Cache::__init();
?>
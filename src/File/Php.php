<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;

// php
// class for a php file
class Php extends TextAlias
{
    // config
    public static $config = [
        'group'=>'php'
    ];
}

// config
Php::__config();
?>
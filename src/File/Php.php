<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\File;
use Quid\Core;
use Quid\Main;

// php
// class for a php file
class Php extends Main\File\Php
{
    // config
    public static $config = [];
}

// init
Php::__init();
?>
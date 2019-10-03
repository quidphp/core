<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Routing;

// request
// extended class with methods to manage an HTTP request
class Request extends Routing\Request
{
    // trait
    use _bootAccess;


    // config
    public static $config = [];
}

// init
Request::__init();
?>
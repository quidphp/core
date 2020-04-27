<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
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
    protected static array $config = [];
}

// init
Request::__init();
?>
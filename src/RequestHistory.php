<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Routing;

// requestHistory
// extended class for a collection containing a history of requests
class RequestHistory extends Routing\RequestHistory
{
    // trait
    use _bootAccess;


    // config
    public static $config = [];
}

// config
RequestHistory::__config();
?>
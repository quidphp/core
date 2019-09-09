<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Routing;

// redirection
// extended class managing a URI redirection array
class Redirection extends Routing\Redirection
{
    // trait
    use _fullAccess;


    // config
    public static $config = [];
}
?>
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
use Quid\Main;

// serviceMailer
// extended abstract class with basic methods that needs to be extended by a mailing service
abstract class ServiceMailer extends Main\ServiceMailer
{
    // trait
    use _fullAccess;


    // config
    public static $config = [
        'queue'=>Row\QueueEmail::class,
        'log'=>Row\LogEmail::class
    ];
}

// init
ServiceMailer::__init();
?>
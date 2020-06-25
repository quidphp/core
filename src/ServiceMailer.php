<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
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
    protected static array $config = [
        'queue'=>Row\QueueEmail::class,
        'log'=>Row\LogEmail::class
    ];
}

// init
ServiceMailer::__init();
?>
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;
use Quid\Main;

// email
// class for a file which is an email (in json format)
class Email extends JsonAlias implements Main\Contract\Email
{
    // trait
    use Main\File\_email;


    // config
    public static $config = [];
}

// config
Email::__config();
?>
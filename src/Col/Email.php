<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// email
// class for a column managing email
class Email extends Core\ColAlias
{
    // config
    public static $config = [
        'validate'=>[1=>'email'],
        'general'=>true,
        'check'=>['kind'=>'char']
    ];
}

// init
Email::__init();
?>
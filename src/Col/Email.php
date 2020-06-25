<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// email
// class for a column managing email
class Email extends Core\ColAlias
{
    // config
    protected static array $config = [
        'validate'=>['email'],
        'general'=>true,
        'keyboard'=>'email',
        'check'=>['kind'=>'char']
    ];
}

// init
Email::__init();
?>
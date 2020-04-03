<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
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
        'keyboard'=>'email',
        'check'=>['kind'=>'char']
    ];
}

// init
Email::__init();
?>
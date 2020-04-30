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

// dateLogin
// class for a column which stores the current timestamp when the user logs in
class DateLogin extends DateAlias
{
    // config
    protected static array $config = [
        'general'=>false,
        'complex'=>'div',
        'date'=>'long',
        'visible'=>['validate'=>'notEmpty']
    ];
}

// init
DateLogin::__init();
?>
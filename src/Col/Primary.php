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

// primary
// class for dealing with a column which has an auto increment primary key
class Primary extends Core\ColAlias
{
    // config
    protected static array $config = [
        'cell'=>Core\Cell\Primary::class,
        'general'=>true,
        'searchMinLength'=>1,
        'order'=>true,
        'editable'=>false,
        'complex'=>'inputHidden',
        'check'=>['kind'=>'int']
    ];
}

// init
Primary::__init();
?>
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
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
        'group'=>'primary',
        'general'=>true,
        'searchMinLength'=>1,
        'order'=>true,
        'editable'=>false,
        'complex'=>'inputHidden',
        'check'=>['kind'=>'int']
    ];


    // isPrimary
    // retourne vrai comme la colonne est la clé primaire
    final public function isPrimary():bool
    {
        return true;
    }
}

// init
Primary::__init();
?>
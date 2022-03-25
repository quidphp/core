<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;
use Quid\Core;
use Quid\Orm;

// num
// class for a column which deals with numeric values (string, float or int)
class Num extends Core\ColAlias
{
    // config
    protected static array $config = [
        'cell'=>Core\Cell\Num::class,
        'keyboard'=>'decimal'
    ];


    // onSet
    // gère la logique onSet pour un champ numérique
    protected function onSet($return,?Orm\Cell $cell,array $row,array $option)
    {
        if(is_scalar($return))
        $return = Base\Num::cast($return,false,true);

        return $return;
    }
}

// init
Num::__init();
?>
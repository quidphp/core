<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cell;

// floating
// class to work with a cell containing a floating value
class Floating extends NumAlias
{
    // config
    protected static array $config = [];


    // pair
    // retourne la date formatté
    // sinon renvoie à parent
    final public function pair($value=null,...$args)
    {
        $return = $this;

        if($value === '$')
        $return = $this->moneyFormat(...$args);

        elseif($value !== null)
        $return = parent::pair($value,...$args);

        return $return;
    }
}

// init
Floating::__init();
?>
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cell;
use Quid\Core;

// integer
// class to manage a cell containing an integer value
class Integer extends Core\CellAlias
{
    // config
    public static $config = [];


    // increment
    // increment la valeur de la cell
    final public function increment():self
    {
        $value = $this->value();
        $value = (is_int($value))? ($value + 1):1;
        $this->set($value);

        return $this;
    }


    // decrement
    // decrement la valeur de la cell
    final public function decrement():self
    {
        $value = $this->value();
        $value = (is_int($value) && $value > 1)? ($value - 1):0;
        $this->set($value);

        return $this;
    }
}

// init
Integer::__init();
?>
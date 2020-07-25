<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cell;
use Quid\Base;
use Quid\Core;

// num
// class to manage a cell containing a numeric value
class Num extends Core\CellAlias
{
    // config
    protected static array $config = [];


    // pair
    // retourne la valeur formatté, sinon renvoie à parent
    public function pair($value=null,...$args)
    {
        $return = $this;

        if($value === '$')
        $return = $this->moneyFormat(...$args);

        else
        $return = parent::pair($value,...$args);

        return $return;
    }


    // cast
    // retourne une version cast de la valeur numérique
    final public function cast()
    {
        return Base\Scalar::cast($this->value(),3);
    }


    // increment
    // increment la valeur de la cell
    final public function increment():self
    {
        $value = $this->cast();
        $value = (is_int($value))? ($value + 1):1;
        $this->set($value);

        return $this;
    }


    // decrement
    // decrement la valeur de la cell
    final public function decrement():self
    {
        $value = $this->cast();
        $value = (is_int($value) && $value > 1)? ($value - 1):0;
        $this->set($value);

        return $this;
    }


    // moneyFormat
    // format le nombre flottant en argent
    final public function moneyFormat(?string $lang=null,?array $option=null):?string
    {
        return Base\Num::moneyFormat($this->cast(),$lang,$option);
    }
}

// init
Num::__init();
?>
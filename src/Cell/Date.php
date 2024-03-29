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

// date
// class to work with a cell containing a date value
class Date extends Core\CellAlias
{
    // config
    protected static array $config = [];


    // pair
    // retourne la date formatté
    // sinon renvoie à parent
    final public function pair($value=null,...$args)
    {
        $return = $this;

        if($value === true || is_int($value) || is_string($value))
        $return = $this->format($value);

        else
        $return = parent::pair($value,...$args);

        return $return;
    }


    // format
    // format la date contenu dans la cellule
    final public function format($format=true):?string
    {
        if($format === true)
        $format = $this->col()->date();

        return ($this->value() !== null)? Base\Datetime::format($format,$this):null;
    }


    // isBefore
    // retourne vrai si le temps est après maintenant ou le temps donné en argument
    // retourne vrai si empty si allowEmpty est true
    final public function isBefore($time=null,bool $allowEmpty=false):bool
    {
        return $this->isBeforeAfter('<=',$time,$allowEmpty);
    }


    // isAfter
    // retourne vrai si le temps est avant maintenant ou le temps donné en argument
    // retourne vrai si empty si allowEmpty est true
    final public function isAfter($time=null,bool $allowEmpty=false):bool
    {
        return $this->isBeforeAfter('>=',$time,$allowEmpty);
    }


    // isBeforeAfter
    // méthode protégé utilisé par isBefore et isAfter
    final protected function isBeforeAfter(string $symbol,$time=null,bool $allowEmpty=false):bool
    {
        $return = false;
        $time = Base\Datetime::time($time);
        $value = $this->value();

        if(is_int($value))
        $return = (Base\Validate::compare($value,$symbol,$time));

        elseif($allowEmpty === true)
        $return = true;

        return $return;
    }
}

// init
Date::__init();
?>
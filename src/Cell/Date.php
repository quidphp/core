<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
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
    public static $config = [];


    // pair
    // retourne la date formatté
    // sinon renvoie à parent
    public function pair($value=null,...$args)
    {
        $return = $this;

        if(is_int($value) || is_string($value))
        $return = $this->format($value);

        elseif($value !== null)
        $return = parent::pair($value,...$args);

        return $return;
    }


    // format
    // format la date contenu dans la cellule
    public function format($format=true):?string
    {
        return Base\Date::format($format,$this);
    }


    // isBefore
    // retourne vrai si le temps est avant maintenant ou le temps donné en argument
    // retourne vrai si empty, si allowEmpty est true
    public function isBefore(bool $allowEmpty=false,$time=null)
    {
        $return = false;
        $time = Base\Date::time($time);
        $value = $this->value();

        if(is_int($value))
        {
            if($value >= $time)
            $return = true;
        }

        elseif($allowEmpty === true)
        $return = true;

        return $return;
    }


    // isAfter
    // retourne vrai si le temps est après maintenant ou le temps donné en argument
    // retourne vrai si empty, si allowEmpty est true
    public function isAfter(bool $allowEmpty=false,$time=null)
    {
        $return = false;
        $time = Base\Date::time($time);
        $value = $this->value();

        if(is_int($value))
        {
            if($value <= $time)
            $return = true;
        }

        elseif($allowEmpty === true)
        $return = true;

        return $return;
    }
}

// init
Date::__init();
?>
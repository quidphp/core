<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Segment;

// _int
// trait that issues a method to deal with a simple integer route segment
trait _int
{
    // structureSegmentInt
    // gère le segment d'uri pour un chiffre entier, int, accepte 0
    public static function structureSegmentInt(string $type,$value,array &$keyValue)
    {
        $return = false;
        $default = static::structureSegmentIntDefault();

        if($type === 'make')
        $return = (is_int($value) && $value >= 0)? $value:false;

        elseif($type === 'match')
        {
            if($value === null)
            $return = $default;

            else
            {
                $return = (is_int($value) && $value >= 0)? $value:false;

                $possible = static::structureSegmentIntPossible();
                if($return !== false && !empty($possible))
                $return = (in_array($value,$possible,true))? $value:false;
            }
        }

        return $return;
    }


    // structureSegmentIntDefault
    // retourne le int par défaut pour le segment
    public static function structureSegmentIntDefault()
    {
        return;
    }


    // structureSegmentIntPossible
    // retourne un tableau avec les int possible pour la route
    public static function structureSegmentIntPossible():?array
    {
        return null;
    }
}
?>
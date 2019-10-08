<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Segment;

// _slug
// trait that issues methods to work with a standard slug route segment
trait _slug
{
    // structureSegmentSlug
    // gère le segment d'uri pour un slug
    public static function structureSegmentSlug(string $type,$value,array &$keyValue)
    {
        $return = false;
        $table = static::tableSegment($keyValue);

        if(!empty($table))
        {
            $rowClass = $table->classe()->row();

            if($type === 'make')
            {
                if(is_int($value))
                $value = $table->row($value);

                if(is_a($value,$rowClass,true))
                $return = $value->cellKey();

                elseif(is_string($value) && !empty($value))
                $return = $value;
            }

            elseif($type === 'match')
            {
                if($value === null)
                $return = static::structureSegmentSlugValidateDefault();

                elseif(is_string($value) && !empty($value))
                $return = $table->row($value) ?? false;

                elseif(is_a($value,$rowClass,true))
                $return = $value;
            }
        }

        return $return;
    }


    // structureSegmentSlugValidateDefault
    // retourne la valeur par défaut pour le segment
    public static function structureSegmentSlugValidateDefault()
    {
        return false;
    }
}
?>
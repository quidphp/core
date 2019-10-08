<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Segment;
use Quid\Base;

// _selected
// trait that provides logic to deal with a route segment which represents a selected value
trait _selected
{
    // structureSegmentSelected
    // gère le segment d'uri pour une valeur sélectionné, la valeur peut être string ou numeric
    public static function structureSegmentSelected(string $type,$value,array &$keyValue)
    {
        $return = false;

        if($type === 'make')
        {
            if(is_object($value))
            $value = Base\Obj::cast($value);

            if(is_array($value))
            $value = implode(static::getDefaultSegment(),$value);

            if(is_string($value) || is_numeric($value))
            $return = $value;
        }

        elseif($type === 'match')
        {
            if($value === null)
            $return = [];
            
            elseif(is_scalar($value))
            {
                $explode = Base\Str::explodeTrimClean(static::getDefaultSegment(),(string) $value);
                $return = Base\Arr::cast($explode);
            }
        }

        return $return;
    }
}
?>
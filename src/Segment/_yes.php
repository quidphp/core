<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Segment;

// _yes
// trait that issues a method to deal with yes route segment (1)
trait _yes
{
    // structureSegmentYes
    // gère le segment d'uri yes
    public static function structureSegmentYes(string $type,$value,array &$keyValue)
    {
        $return = false;

        if($type === 'make' && $value === 1)
        $return = $value;

        elseif($type === 'match')
        {
            if($value === null)
            $return = null;
            
            elseif($value === 1)
            $return = $value;
        }
        
        return $return;
    }
}
?>
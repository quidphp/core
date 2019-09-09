<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Segment;

// _str
// trait that issues a method to deal with a simple string route segment
trait _str
{
    // structureSegmentStr
    // gère le segment d'uri pour une simple string, très permissif
    public static function structureSegmentStr(string $type,$value,array &$keyValue)
    {
        $return = false;

        if($type === 'make' && is_string($value) && strlen($value))
        $return = $value;

        elseif($type === 'validate' && is_string($value) && strlen($value))
        $return = $value;

        return $return;
    }
}
?>
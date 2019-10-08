<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Segment;
use Quid\Core;

// _col
// trait to manage a route segment which must contain a column name or object
trait _col
{
    // structureSegmentCol
    // gère le segment d'uri pour colonne
    public static function structureSegmentCol(string $type,$value,array &$keyValue)
    {
        $return = false;

        if($type === 'make')
        {
            if(is_string($value) || $value instanceof Core\Col)
            $return = $value;

            elseif($value instanceof Core\Cell)
            $return = $value->col();
        }

        elseif($type === 'match')
        {
            $table = static::tableSegment($keyValue);

            if(!empty($table) && $table->hasCol($value))
            $return = $table->col($value);
        }

        return $return;
    }
}
?>
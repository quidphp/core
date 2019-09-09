<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Segment;
use Quid\Core;

// _colRelation
// trait to work with a route segment which must contain a column with a relation
trait _colRelation
{
    // structureSegmentColRelation
    // gère le segment d'uri pour colonne qui doit être de relation
    public static function structureSegmentColRelation(string $type,$value,array &$keyValue)
    {
        $return = false;

        if($type === 'make')
        {
            if(is_string($value) || $value instanceof Core\Col)
            $return = $value;

            elseif($value instanceof Core\Cell)
            $return = $value->col();
        }

        elseif($type === 'validate')
        {
            $table = static::tableSegment($keyValue);
            if(!empty($table) && $table->hasCol($value))
            {
                $col = $table->col($value);

                if($col->canRelation())
                $return = $col;
            }
        }

        return $return;
    }
}
?>
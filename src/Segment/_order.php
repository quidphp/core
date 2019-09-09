<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Segment;
use Quid\Core;

// _order
// trait to manage a route segment which must contain an orderable column
trait _order
{
    // structureSegmentOrder
    // gère le segment d'uri pour un ordre, similaire à colonne
    public static function structureSegmentOrder(string $type,$value,array &$keyValue)
    {
        $return = false;

        if($type === 'make')
        $return = (is_string($value) || $value instanceof Core\Col)? $value:false;

        else
        {
            $table = static::tableSegment($keyValue);

            if(!empty($table))
            {
                if($type === 'validate')
                {
                    if($table->hasCol($value))
                    {
                        $col = $table->col($value);
                        if($col->isOrderable())
                        $return = $col;
                    }
                }

                elseif($type === 'validateDefault')
                $return = $table->order('order');
            }
        }

        return $return;
    }
}
?>
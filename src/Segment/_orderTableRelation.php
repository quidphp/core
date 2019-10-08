<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Segment;

// _orderTableRelation
// trait to manage a route segment which must contain an orderable table relation
trait _orderTableRelation
{
    // structureSegmentOrderTableRelation
    // gère le segment order pour une route relation
    public static function structureSegmentOrderTableRelation(string $type,$value,array &$keyValue)
    {
        $return = false;

        if($type === 'make')
        $return = (is_scalar($value))? $value:false;

        elseif($type === 'match')
        {
            if($value === null)
            $return = static::$config['order'] ?? false;

            else
            {
                $db = static::db();
                if($db->hasTable($keyValue['table']))
                {
                    $table = $db->table($keyValue['table']);

                    if(static::isValidOrder($value,$table->relation()))
                    $return = $value;
                }
            }
        }

        return $return;
    }
}
?>
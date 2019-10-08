<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Segment;
use Quid\Base;
use Quid\Core;

// _cols
// trait to manage a route segment which must contain many columns
trait _cols
{
    // structureSegmentCols
    // gère le segment d'uri pour plusieurs colonnes
    public static function structureSegmentCols(string $type,$value,array &$keyValue)
    {
        $return = false;

        if($type === 'make')
        {
            if(!empty($value))
            {
                $default = static::getDefaultSegment();

                if($value instanceof Core\Cols)
                $value = $value->names();

                if(is_array($value))
                $value = implode($default,$value);

                if(is_string($value))
                $return = $value;
            }
        }

        elseif($type === 'match')
        {
            $table = static::tableSegment($keyValue);

            if(!empty($table))
            {
                if($value === null)
                $return = $table->cols()->general()->filter(['isVisibleGeneral'=>true]);

                elseif(is_string($value) && !empty($value))
                {
                    $default = static::getDefaultSegment();

                    $array = Base\Str::explodeTrimClean($default,$value);
                    $count = count($array);
                    $value = $table->cols(...$array)->filter(['isVisibleGeneral'=>true]);

                    if($value->isCount($count))
                    $return = $value;
                }

                elseif($value instanceof Core\Cols)
                $return = $value;
            }
        }

        return $return;
    }
}
?>
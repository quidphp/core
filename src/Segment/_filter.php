<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Segment;
use Quid\Core;
use Quid\Base;

// _filter
// trait to manage a complex route segment which contains filtering directive
trait _filter
{
    // structureSegmentFilter
    // gère le segment d'uri pour un filtre, peut en contenir plusieurs
    public static function structureSegmentFilter(string $type,$value,array &$keyValue)
    {
        $return = false;

        if($type === 'validateDefault')
        $return = [];

        else
        {
            $table = static::tableSegment($keyValue);

            if(!empty($table))
            {
                if($type === 'make')
                $return = static::makeSegmentFilter($value,$table);

                elseif($type === 'validate')
                $return = static::validateSegmentFilter($value,$table);
            }
        }

        return $return;
    }


    // makeSegmentFilter
    // gère le segment filter lors de la création d'une uri
    protected static function makeSegmentFilter($value,Core\Table $table)
    {
        $return = false;

        if(!empty($value))
        {
            if(is_array($value))
            {
                $array = [];
                $delimiters = static::getFilterDelimiters();

                foreach ($value as $k => $v)
                {
                    $str = null;

                    if($v instanceof Core\Row)
                    $v = $v->primary();

                    if(is_scalar($v))
                    $v = [$v];

                    if(is_array($v) && !empty($v))
                    {
                        $str = $k.$delimiters[1];
                        foreach (array_values($v) as $i => $vv)
                        {
                            if($i > 0)
                            $str .= $delimiters[2];

                            $str .= $vv;
                        }
                    }

                    if(!empty($str))
                    $array[] = $str;
                }

                if(!empty($array))
                $return = implode($delimiters[0],$array);
            }

            elseif(is_string($value) && strlen($value))
            $return = $value;
        }

        return $return;
    }


    // validateSegmentFilter
    // gère le segment filter lors de la validation d'une uri
    protected static function validateSegmentFilter($value,Core\Table $table)
    {
        $return = false;

        if(is_string($value))
        {
            $return = [];
            $delimiters = static::getFilterDelimiters();
            $array = Base\Str::explodes([$delimiters[0],$delimiters[1]],$value,null,true,true);

            if(!empty($array))
            {
                foreach ($array as $val)
                {
                    if(is_array($val) && count($val) === 2)
                    {
                        $k = $val[0];
                        $v = Base\Str::explode($delimiters[2],$val[1],null,true,true);

                        if(is_string($k) && $table->hasCol($k) && !empty($v))
                        {
                            $col = $table->col($k);
                            $v = Base\Arr::cast($v);

                            if($col->isFilterable())
                            {
                                $rel = $col->relation();

                                foreach ($v as $vv)
                                {
                                    if($rel->exists($vv))
                                    $return[$k][] = $vv;

                                    else
                                    {
                                        $return = false;
                                        break 2;
                                    }
                                }
                            }

                            else
                            {
                                $return = false;
                                break;
                            }
                        }
                    }
                }
            }
        }

        return $return;
    }


    // getFilterDelimiters
    // retourne les délimiteurs à utiliser pour les filtres
    public static function getFilterDelimiters():array
    {
        $return = [];
        $default = static::getDefaultSegment();
        $return[] = $default.$default.$default;
        $return[] = $default.$default;
        $return[] = $default;

        return $return;
    }
}
?>
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cell;
use Quid\Core;
use Quid\Orm;

// relation
// abstract class extended by the enum and set cells
abstract class Relation extends Core\CellAlias
{
    // config
    public static $config = [];


    // pair
    // si value est true, la relation, relationRow ou relationRows si c'est une relation de table
    // sinon renvoie à parent
    public function pair($value=null,...$args)
    {
        $return = $this;

        if($value === true)
        {
            if($this->isRelationTable())
            $return = $this->colRelation()->getRow($this);

            else
            $return = $this->relation();
        }

        elseif($value !== null)
        $return = parent::pair($value,...$args);

        return $return;
    }


    // export
    // retourne la valeur pour l'exportation de cellules relation
    public function export(?array $option=null):array
    {
        $return = [];
        $value = $this->relationKeyValue($option);

        if(is_array($value))
        $value = array_values($value);

        $return = $this->exportCommon($value,$option);

        return $return;
    }


    // colRelation
    // retourne l'objet relation de la colonne, si existant
    // envoie une exception sinon
    public function colRelation():Orm\ColRelation
    {
        return $this->col()->relation();
    }


    // relationKeyValue
    // retourne la valeur de la relation sous forme d'un tableau key -> value, peu importe si c'est enum ou set
    public function relationKeyValue(?array $option=null):?array
    {
        return $this->colRelation()->getKeyValue($this,false,true,$option);
    }


    // isRelationTable
    // retourne vrai si la colonne est une relation avec une autre table
    public function isRelationTable():bool
    {
        return $this->colRelation()->isRelationTable();
    }


    // relationTable
    // retourne la table de la relation, si existante
    public function relationTable():?Core\Table
    {
        return $this->colRelation()->relationTable();
    }


    // generalOutput
    // génère le output pour général
    public function generalOutput(?array $option=null):?string
    {
        $return = null;
        $relation = $this->relationKeyValue();

        if(!empty($relation))
        {
            $col = $this->col();
            $max = $this->attr('generalMax');
            $route = $this->routeClassSafe('specific');
            $total = count($relation);
            $separator = ', ';
            $array = $col->makeRelationPlainArray($relation,$max,$route);

            if(!empty($array))
            {
                $return = implode($separator,$array);

                if($total > $max)
                {
                    $diff = ($total - $max);
                    $return .= " (+$diff)";
                }
            }
        }

        return $return;
    }
}

// init
Relation::__init();
?>
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Cell;
use Quid\Core;
use Quid\Orm;

// relation
// abstract class extended by the enum and set cells
abstract class Relation extends Core\CellAlias
{
    // config
    public static array $config = [];


    // pair
    // si value est true, la relation, relationRow ou relationRows si c'est une relation de table
    // sinon renvoie à parent
    final public function pair($value=null,...$args)
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
    final public function export(?array $option=null):array
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
    final public function colRelation():Orm\ColRelation
    {
        return $this->col()->relation();
    }


    // relationKeyValue
    // retourne la valeur de la relation sous forme d'un tableau key -> value, peu importe si c'est enum ou set
    final public function relationKeyValue(?array $option=null):?array
    {
        return $this->colRelation()->getKeyValue($this,false,true,$option);
    }


    // isRelationTable
    // retourne vrai si la colonne est une relation avec une autre table
    final public function isRelationTable():bool
    {
        return $this->colRelation()->isRelationTable();
    }


    // relationTable
    // retourne la table de la relation, si existante
    final public function relationTable():?Core\Table
    {
        return $this->colRelation()->relationTable();
    }
}

// init
Relation::__init();
?>
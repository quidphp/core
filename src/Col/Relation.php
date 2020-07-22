<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;
use Quid\Base\Html;
use Quid\Core;
use Quid\Orm;

// relation
// abstract class extended by the enum and set columns
abstract class Relation extends Core\ColAlias
{
    // config
    protected static array $config = [
        'search'=>false,
        'group'=>'relation',
        'filter'=>true,
        'order'=>false,
        'relation'=>null,
        'detailMaxLength'=>false,
        'check'=>['null'=>true], // les relations doivent être nullables
        'inRelation'=>true,
        'generalMax'=>3,
        'relationHtml'=>"<div class='choice'><div class='choice-in'>%</div></div>", // html pour la relation
    ];


    // prepareAttr
    // gère les attributs pour relation
    final protected function prepareAttr(array $return,Orm\ColSchema $schema):array
    {
        $return = parent::prepareAttr($return,$schema);
        $table = $this->table();

        if(!isset($return['relation']))
        $return['relation'] = $schema->relation();

        if($table->getAttr('inRelation') === true && !empty($return['inRelation']))
        $return['validate']['inRelation'] = $this->inRelationClosure();

        return $return;
    }


    // onSet
    // lors du set d'une valeur de relation
    protected function onSet($return,?Orm\Cell $cell=null,array $row,array $option)
    {
        return $this->autoCastRelation($return);
    }


    // isRelation
    // retourne vrai comme la colonne est une relation
    final public function isRelation():bool
    {
        return true;
    }


    // isEnum
    // retourne vrai si la colonne est de type relation enum
    public function isEnum():bool
    {
        return false;
    }


    // isSet
    // retourne vrai si la colonne est de type relation set
    public function isSet():bool
    {
        return false;
    }


    // inRelationClosure
    // méthode anonyme pour valider si la valeur est bien dans la relation
    final protected function inRelationClosure():\Closure
    {
        return function(string $context,$value=null) {
            $return = null;

            if($context === 'validate')
            {
                $return = true;

                if(is_scalar($value) || is_array($value))
                {
                    $relation = $this->relation();

                    if(is_string($value))
                    $value = Base\Set::arr($value);

                    $values = array_values((array) $value);
                    $values = Base\Arr::cast($values);

                    if(!$relation->exists(...$values))
                    $return = false;
                }
            }

            return $return;
        };
    }


    // isRelationTable
    // retourne vrai si la relation est de type table
    final public function isRelationTable():bool
    {
        return $this->relation()->isRelationTable();
    }


    // relationTable
    // retourne la table de relation si existante
    final public function relationTable():?Core\Table
    {
        return $this->relation()->relationTable();
    }


    // hasFormLabelId
    // retourne vrai si l'élément de label doit avoir un id
    final public function hasFormLabelId(?array $attr=null,bool $complex=false):bool
    {
        return ($complex === true && $this->tag($attr,$complex) === 'search')? true:parent::hasFormLabelId($attr,$complex);
    }


    // tag
    // retourne la tag à utiliser pour représenter la relation
    // override pour complex
    final public function tag(?array $attr=null,bool $complex=false):string
    {
        $return = null;

        if(empty($attr['tag']) && $complex === true)
        {
            $complex = $this->getAttr('complex');

            if(is_array($complex) && !empty($complex))
            {
                $relation = $this->relation();
                $size = $relation->size(true);

                foreach ($complex as $key => $value)
                {
                    if($size < $key)
                    break;

                    $return = $value;
                }
            }

            else
            $return = $complex;
        }

        return $return ?? parent::tag($attr,false);
    }


    // prepareRelationPlainGeneral
    // méthode utilisé pour préparer l'affichage des relations plains (sans formulaire)
    // retourne au maximum le generalMax
    public function prepareRelationPlainGeneral(array $array):array
    {
        $return = [];
        $max = $this->getAttr('generalMax');
        $i = 0;

        foreach ($array as $key => $value)
        {
            if(is_scalar($value))
            {
                if($max === null || $i < $max)
                {
                    $return[$key] = $value;
                    $i++;
                }

                else
                break;
            }
        }

        return $return;
    }
}

// init
Relation::__init();
?>
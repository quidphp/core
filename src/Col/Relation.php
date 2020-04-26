<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Col;
use Quid\Base;
use Quid\Base\Html;
use Quid\Core;

// relation
// abstract class extended by the enum and set columns
abstract class Relation extends Core\ColAlias
{
    // config
    public static array $config = [
        'search'=>false,
        'filter'=>true,
        'order'=>false,
        'onSet'=>[Base\Set::class,'onSet'],
        'generalExcerptMin'=>null,
        'check'=>['null'=>true], // les relations doivent être nullables
        'inRelation'=>true,
        'generalMax'=>3,
        'relationHtml'=>"<div class='choice'><div class='choice-in'>%</div></div>", // html pour la relation
    ];


    // onMakeAttr
    // gère onMakeAttr pour relation
    final protected function onMakeAttr(array $return):array
    {
        $table = $this->table();

        if($table->getAttr('inRelation') === true && !empty($return['inRelation']))
        $return['validate']['inRelation'] = $this->inRelationClosure();

        return $return;
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


    // showDetailsMaxLength
    // n'affiche pas le détail sur le maxLength de la colonne
    final public function showDetailsMaxLength():bool
    {
        return false;
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

        if($return === null)
        $return = parent::tag($attr,false);

        return $return;
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
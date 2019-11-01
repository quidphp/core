<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
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
    public static $config = [
        'search'=>false,
        'filter'=>true,
        'order'=>false,
        'onSet'=>[Base\Set::class,'onSet'],
        'generalExcerptMin'=>null,
        'check'=>['null'=>true], // les relations doivent être nullables
        'inRelation'=>true,
        'generalMax'=>3,
        'relationHtml'=>"<div class='choice'><div class='choice-in'>%</div></div>", // html pour la relation
        'route'=>[ // route à ajouter
            'specific'=>null,
            'specificRelation'=>null]
    ];


    // onMakeAttr
    // gère onMakeAttr pour relation
    protected function onMakeAttr(array $return):array
    {
        $table = $this->table();

        if($table->getAttr('inRelation') === true && !empty($return['inRelation']))
        $return['validate']['inRelation'] = $this->inRelationClosure();

        return $return;
    }


    // inRelationClosure
    // méthode anonyme pour valider si la valeur est bien dans la relation
    protected function inRelationClosure():\Closure
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
    public function isRelationTable():bool
    {
        return $this->relation()->isRelationTable();
    }


    // showDetailsMaxLength
    // n'affiche pas le détail sur le maxLength de la colonne
    public function showDetailsMaxLength():bool
    {
        return false;
    }


    // relationTable
    // retourne la table de relation si existante
    public function relationTable():?Core\Table
    {
        return $this->relation()->relationTable();
    }


    // hasFormLabelId
    // retourne vrai si l'élément de label doit avoir un id
    public function hasFormLabelId(?array $attr=null,bool $complex=false):bool
    {
        return ($complex === true && $this->tag($attr,$complex) === 'search')? true:parent::hasFormLabelId($attr,$complex);
    }


    // tag
    // retourne la tag à utiliser pour représenter la relation
    // override pour complex
    public function tag(?array $attr=null,bool $complex=false):string
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


    // formComplex
    // génère un élément de formulaire pour les relations
    public function formComplex($value=true,?array $attr=null,?array $option=null):string
    {
        $return = '';
        $tag = $this->complexTag($attr);
        $relation = $this->relation();
        $size = $relation->size();

        if($size > 0)
        {
            $method = 'formComplex'.ucfirst($tag);

            if(method_exists($this,$method))
            $return = $this->$method($value,$attr,$option);

            elseif(Html::isRelationTag($tag))
            $return = $this->formComplexStandard($value,$attr,$option);

            elseif(Html::isFormTag($tag,true))
            $return = $this->form($value,$attr,$option);

            else
            $return = $this->formComplexPlain($value,$attr,$option);
        }

        else
        $return = $this->formComplexNothing();

        return $return;
    }


    // formComplexSearch
    // génère un élément de formulaire pour la recherche
    protected function formComplexSearch($value=true,?array $attr=null,?array $option=null):string
    {
        $return = '';
        $option = Base\Arr::plus(['button'=>true],$option);
        $rel = $this->relation();
        $mode = $rel->mode();
        $size = $rel->size();
        $lang = $this->db()->lang();

        $route = $this->route('specificRelation',['table'=>$this->table(),'col'=>$this,'selected'=>true]);
        $query = $route->getSearchQuery();

        $placeholder = $attr['placeholder'] ?? $lang->text('common/search');
        if(is_array($attr) && array_key_exists('placeholder',$attr))
        unset($attr['placeholder']);

        if($placeholder === true)
        $placeholder = $rel->label();

        if(is_string($placeholder))
        $placeholder .= " ($size)";

        $searchMinLength = ($rel->isRelationTable())? $rel->relationTable()->searchMinLength():$this->table()->searchMinLength();
        $required = ($this->getAttr('relationSearchRequired') === true)? true:null;

        $data = ['query'=>$query,'separator'=>$route::getDefaultSegment(),'required'=>$required,'char'=>$route::getReplaceSegment(),'pattern'=>['minLength'=>$searchMinLength]];
        if($route->hasOrder())
        $route = $route->changeSegment('order',true);
        $data['href'] = $route;

        $id = $attr['id'] ?? null;
        if(is_array($attr) && array_key_exists('id',$attr))
        unset($attr['id']);

        $return .= Html::divOp(['search-enumset',$mode,'data-mode'=>$mode]);
        $return .= Html::divOp('input');
        $return .= Html::inputText(null,['placeholder'=>$placeholder,'name'=>true,'data'=>$data,'id'=>$id]);

        if($option['button'] === true)
        $return .= Html::button(null,['icon','solo','search']);

        $return .= Html::divOp('popup');
        $return .= $route->orderSelect();
        $return .= Html::div(null,'results');
        $return .= Html::divCl();

        $return .= Html::divCl();
        $return .= Html::divOp('current');
        $return .= $this->formHidden();
        $return .= $this->formComplexSearchChoices($value,$attr,$option);
        $return .= Html::divCl();
        $return .= Html::divCl();

        return $return;
    }


    // formComplexSearchChoices
    // génère un checkbox à partir de la relation
    public function formComplexSearchChoices($value,?array $attr=null,?array $option=null):string
    {
        $return = '';
        $value = $this->valueComplex($value,$option);
        $rel = $this->relation();
        $relation = $rel->getKeyValue($value);
        $option = (array) $option;
        $option = $this->prepareChoiceOption($option,false);

        if(is_array($relation) && !empty($relation))
        {
            $tag = ($this->isSet())? 'checkbox':'radio';
            $attr = Base\Arr::plus($attr,['tag'=>$tag]);
            $option = Base\Arr::plus($option,['value'=>$value]);
            $relation = $this->valueExcerpt($relation);
            $relation = $this->prepareRelationRadioCheckbox($relation);
            $return = $this->formComplexOutput($relation,$attr,$option);
        }

        return $return;
    }


    // formComplexStandard
    // génère un champ pour relation standard comme select, radio, checkbox et multiselect
    public function formComplexStandard($value,?array $attr=null,?array $option=null):string
    {
        $return = '';
        $value = $this->valueComplex($value,$option);
        $rel = $this->relation();
        $tag = $this->complexTag($attr);
        $attr = Base\Arr::plus($attr,['tag'=>$tag]);
        $option = Base\Arr::plus($option,['value'=>$value]);
        $relation = $this->prepareStandardRelation($value);
        $relation = $this->valueExcerpt($relation);

        if($tag === 'select' && !array_key_exists('title',$option))
        $option['title'] = true;

        if(in_array($tag,['radio','checkbox'],true))
        {
            $option = $this->prepareChoiceOption($option,true);
            $relation = $this->prepareRelationRadioCheckbox($relation);
        }

        $return .= $this->formComplexOutput($relation,$attr,$option);

        return $return;
    }


    // prepareStandardRelation
    // retourne la relation pour un input avec choice
    // désactive la cache
    // méthode pouvant être étendu
    protected function prepareStandardRelation($value):array
    {
        $return = $this->relation()->all(false);

        return $return;
    }


    // prepareChoiceOption
    // retourne le html pour wrapper les choix
    // méthode pouvant être étendu
    protected function prepareChoiceOption(array $return,bool $autoHidden=false):array
    {
        $return['autoHidden'] = $autoHidden;
        $return['html'] = $this->getAttr('relationHtml');

        return $return;
    }


    // formComplexPlain
    // génère un élément de formulaire plain, c'est à dire sans balise de formulaire (comme une div)
    public function formComplexPlain($value,?array $attr=null,?array $option=null):string
    {
        $return = '';
        $tag = $this->complexTag($attr);
        $value = $this->valueComplex($value,$option);
        $relation = $this->relation();

        if($relation->isRelationTable())
        {
            $table = $relation->relationTable();
            $value = $relation->getKeyValue($value,true,true,$option);

            if(!empty($value))
            {
                $value = $this->valueExcerpt($value);
                $value = $this->prepareRelationPlainGeneral($value);
                $value = $this->relationPlainHtml($value);
                $return .= $this->formComplexOutput($value,$attr,$option);
            }
        }

        else
        {
            $value = $relation->get($value,true,true,$option);

            if($value !== null)
            {
                $value = $this->valueExcerpt($value);

                if(!Base\Html::isFormTag($tag))
                $value = $this->relationPlainHtml($value);

                $return .= $this->formComplexOutput($value,$attr,$option);
            }
        }

        if(empty($return))
        $return = $this->formComplexEmptyPlaceholder($value);

        return $return;
    }


    // relationPlainHtml
    // fait le html pour chaque choix de relation, lorsque le input est plain
    public function relationPlainHtml($array):string
    {
        $return = '';

        if(!is_array($array))
        $array = (array) $array;

        foreach ($array as $value)
        {
            $return .= Html::divCond($value,'relation-plain');
        }

        return $return;
    }


    // prepareRelationRadioCheckbox
    // méthode utilisé lors de la préparation d'une valeur relation radio ou checkbox, incluant search
    protected function prepareRelationRadioCheckbox(array $return):array
    {
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
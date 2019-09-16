<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;
use Quid\Core;

// set
// class for a column containing a set relation (many)
class Set extends RelationAlias
{
    // config
    public static $config = [
        'cell'=>Core\Cell\Set::class,
        'onGet'=>[Base\Set::class,'onGet'],
        'preValidate'=>'array',
        'set'=>true,
        'complex'=>[0=>'checkbox',11=>'search'],
        'check'=>['kind'=>'text'],
        'sortable'=>true
    ];


    // isSet
    // retourne vrai comme la colonne est de type relation set
    public function isSet():bool
    {
        return true;
    }


    // isSortable
    // retourne vrai si la relation est sortable
    public function isSortable():bool
    {
        $return = false;

        if($this->attr('sortable') === true)
        {
            $tag = $this->complexTag();
            $relation = $this->relation();

            if(in_array($tag,['checkbox','search'],true) && $relation->size() > 1)
            $return = true;
        }

        return $return;
    }


    // onGet
    // envoie une exception si le retour de onGet n'est pas array ou null (seulement si pas dans contexte general)
    public function onGet($return,array $option)
    {
        $return = parent::onGet($return,$option);

        if(empty($option['context']) || (is_string($option['context']) && strpos($option['context'],':general') === false))
        {
            if(!$return instanceof Core\Cell && !is_array($return) && $return !== null)
            static::throw($this);
        }

        return $return;
    }


    // classHtml
    // retourne la ou les classe à utiliser en html pour sortable
    public function classHtml()
    {
        $return = [parent::classHtml()];

        if($this->isSortable())
        $return[] = 'sortable';

        return $return;
    }


    // prepareStandardRelation
    // retourne la relation pour un input avec choice
    // si sortable, met les éléments cochés en ordre au début de la liste
    protected function prepareStandardRelation($value):array
    {
        $return = [];

        if($this->isSortable())
        {
            $relation = $this->relation();
            $all = $relation->all();

            if(is_string($value))
            $value = Base\Set::arr($value);

            if(is_array($value))
            {
                $return = Base\Arr::gets($value,$all);
                $all = Base\Arr::unsets($value,$all);
                $return = Base\Arr::append($return,$all);
            }

            if(empty($return))
            $return = $all;
        }

        else
        $return = parent::prepareStandardRelation($value);

        return $return;
    }


    // prepareChoiceOption
    // retourne le html pour wrapper les choix
    protected function prepareChoiceOption(array $return,bool $autoHidden=false):array
    {
        $return = parent::prepareChoiceOption($return,$autoHidden);

        if($this->isSortable())
        {
            $return['html'] = "<div class='choice'><div class='choiceInner'><div class='icon solo move'></div>";
            $return['html'] .= '%';
            $return['html'] .= '</div></div>';
        }

        return $return;
    }
}

// config
Set::__config();
?>
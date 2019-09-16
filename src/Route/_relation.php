<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Base;
use Quid\Base\Html;
use Quid\Orm;

// _relation
// trait that provides common methods related to a relation route
trait _relation
{
    // trait
    use _search;


    // config
    public static $configRelation = [
        'limit'=>20,
        'order'=>null, // ordre par défaut
        'query'=>['q'],
        'search'=>['query'=>'q']
    ];


    // relation
    // retourne l'objet relation
    abstract public function relation():Orm\Relation;


    // limit
    // retourne la limite à utiliser
    // public car utilisé via d'autres routes
    public function limit():int
    {
        return static::$config['limit'];
    }


    // hasPage
    // retourne vrai si la route gère les pages
    public function hasPage():bool
    {
        return ($this->hasSegment('page'))? true:false;
    }


    // pageNext
    // retourne la prochaine page si existante
    public function pageNext():?int
    {
        $return = null;

        if($this->hasPage())
        {
            $page = ($this->segment('page') + 1);
            $limit = $this->limit();
            $limit = [$page=>$limit];
            $option = ['limit'=>$limit];

            $relation = $this->relationSearch($option);

            if(!empty($relation))
            $return = $page;
        }

        return $return;
    }


    // relationSearchNot
    // retourne le not à utiliser pour relationSearch
    protected function relationSearchNot()
    {
        return;
    }


    // loadMore
    // génère le html pour loadMore si relation a page
    protected function loadMore():string
    {
        $r = '';
        $pageNext = $this->pageNext();

        if(is_int($pageNext))
        {
            $route = $this->changeSegment('page',$pageNext);
            $data = ['href'=>$route];
            $r .= Html::liOp(['loadMore','data'=>$data]);
            $r .= Html::div(static::langText('common/loadMore'),'text');
            $r .= Html::liCl();
        }

        return $r;
    }


    // hasOrder
    // retourne vrai si la route gère l'ordre
    public function hasOrder():bool
    {
        $return = false;
        $relation = $this->relation();

        if($this->hasSegment('order') && static::$config['order'] === true && $relation->size() > 1)
        {
            $order = $this->segment('order');

            if($order === null || static::isValidOrder($order,$relation) || $order === static::getReplaceSegment())
            $return = true;
        }

        return $return;
    }


    // currentOrder
    // retourne l'ordre courant de la route
    public function currentOrder():?int
    {
        $return = null;

        if($this->hasOrder())
        {
            $return = $this->segment('order');

            if(!is_int($return))
            $return = $this->relation()->defaultOrderCode();
        }

        return $return;
    }


    // orderSelect
    // génère le menu de sélection pour le choix d'ordre
    public function orderSelect():string
    {
        $return = '';
        $order = $this->currentOrder();
        $relation = $this->relation();
        $orders = static::validOrders($relation);

        if(is_int($order) && is_array($orders) && !empty($orders))
        {
            $select = Html::select($orders,['name'=>true],['selected'=>$order]);
            $return = Html::divCond($select,'order');
        }

        return $return;
    }


    // validOrders
    // retourne les ordres valables pour la route
    public static function validOrders(object $relation):array
    {
        $return = [];
        $lang = static::lang();
        $allowed = $relation->allowedOrdering();

        if(!empty($allowed['key']))
        $return = $lang->take('relationOrder/key');

        if(!empty($allowed['value']))
        $return = Base\Arr::append($return,$lang->take('relationOrder/value'));

        return $return;
    }


    // isValidOrder
    // retourne vrai si l'ordre est valable pour la route
    public static function isValidOrder($value,object $relation):bool
    {
        $return = false;
        $orders = static::validOrders($relation);

        if(is_scalar($value) && !empty($orders) && array_key_exists((int) $value,$orders))
        $return = true;

        return $return;
    }
}
?>
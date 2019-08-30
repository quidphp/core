<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Base\Html;
use Quid\Core;
use Quid\Orm;
use Quid\Base;

// _tableRelation
// trait that provides methods to make a table relation route, used by some inputs
trait _tableRelation
{
	// trait
	use _relation;


	// relation
	// retourne l'objet relation de la table
	public function relation():Orm\Relation
	{
		return $this->segment('table')->relation();
	}


	// trigger
	// lance la route tableRelation
	public function trigger():string
	{
		$r = '';
		$results = $this->relationSearch();

		$r .= Html::divOp('relationWrap');

		if(is_array($results) && !empty($results))
		$r .= $this->makeResults($results,'list',true);

		else
		$r .= Base\Html::h3(static::langText('common/nothing'));

		$r .= Html::divCl();

		return $r;
	}


	// isSearchValueValid
	// retourne vrai si la valeur de recherche est valide
	protected function isSearchValueValid(string $value):bool
	{
		return ($this->segment('table')->isSearchTermValid($value))? true:false;
	}


	// relationKeyValue
	// retourne les keyValue à partir de valeur de relation
	protected function relationKeyValue(array $values):?array
	{
		$return = null;
		$table = $this->segment('table');
		$return = $table->relation()->gets($values,true,false);

		return $return;
	}


	// relationSearch
	// lance la recherche de relation
	protected function relationSearch(?array $option=null):?array
	{
		$return = null;
		$table = $this->segment('table');
		$method = static::$config['method'] ?? null;
		$base = ['limit'=>$this->limit(),'not'=>$this->relationSearchNot(),'method'=>$method,'order'=>$this->currentOrder()];
		$base = Base\Arr::clean($base);
		$option = Base\Arr::plus($base,$option);

		$relation = $table->relation();
		$search = $this->getSearchValue();
		$required = false;

		if($this->hasPage() && is_int($option['limit']))
		{
			$page = $this->segment('page');
			$option['limit'] = [$page=>$option['limit']];
		}

		if($required === false || $search !== null)
		{
			$return = [];

			if(is_string($search))
			$return = $relation->search($search,$option);

			elseif($search === null)
			$return = $relation->all(false,$option);
		}

		return $return;
	}


	// makeResults
	// génère les résultats d'affichage de la relation
	protected function makeResults(array $array,$attr=null,bool $loadMore=false):string
	{
		$r = '';

		if(!empty($array))
		{
			$r .= Html::ulOp($attr);

			foreach ($array as $key => $value)
			{
				$r .= Html::li($value);
			}

			if(!empty($r) && $loadMore === true)
			$r .= $this->loadMore();

			$r .= Html::ulCl();
		}

		return $r;
	}


	// makeClickOpen
	// génère le html pour le clickOpen
	public static function makeClickOpen(Core\Table $table,Core\Route $route,$class=null):string
	{
		$r = '';
		$html = '';
		$relation = $table->relation();
		$size = $relation->size();
		$label = $table->label();
		$after = null;

		$limit = $route->limit();
		$query = $route::getSearchQuery();
		$data = ['query'=>$query,'separator'=>static::getDefaultSegment(),'char'=>static::getReplaceSegment()];
		if($route->hasOrder())
		$route = $route->changeSegment('order',true);
		$data['href'] = $route;

		if($size > $limit)
		{
			$searchMinLength = $table->searchMinLength();
			$order = $route->orderSelect();

			$html .= Html::divOp('top');
			$placeholder = static::langText('common/filter')." ($size)";
			$html .= Html::inputText(null,['name'=>true,'data-pattern'=>['minLength'=>$searchMinLength],'placeholder'=>$placeholder]);

			if(!empty($order))
			{
				$html .= Html::div(null,'spacing');
				$html .= $order;
			}

			$html .= Html::divCl();
		}

		$html .= Html::div(null,'result');
		$r .= Html::clickOpen($html,$label,$after,[$class,'data'=>$data]);

		return $r;
	}
}
?>
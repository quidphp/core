<?php
declare(strict_types=1);
namespace Quid\Core\Route;
use Quid\Base\Html;
use Quid\Core;
use Quid\Base;

// _generalRelation
trait _generalRelation
{
	// trait
	use _colRelation;
	
	
	// getRoute
	// retourne la route à utiliser
	abstract protected function getRoute():Core\Route;
	
	
	// col
	// retourne l'objet colonne
	public function col():Core\Col
	{
		return $this->segment('col');
	}
	
	
	// trigger
	// lance la route generalRelation
	public function trigger():string
	{
		$r = '';
		$results = $this->relationSearch();
		$selected = $this->segment('selected');
		
		$r .= Html::divOp('relationWrap');
		
		if(!empty($selected))
		{
			$selected = $this->relationKeyValue($selected);
			$r .= $this->makeResults($selected,'selectedList');
		}
		
		if(is_array($results) && !empty($results))
		$r .= $this->makeResults($results,'list',true);
		
		else
		$r .= Base\Html::h3(static::langText('common/nothing'));
		
		$r .= Html::divCl();
		
		return $r;
	}
	
	
	// relationSearchNot
	// retourne le not à utiliser pour relationSearch
	protected function relationSearchNot()
	{
		return $this->segment('selected');
	}
	
	
	// makeRoutes
	// retourne un tableau avec toutes les routes de filtre à afficher
	protected function makeRoutes(array $array):array
	{
		$return = [];
		
		if(!empty($array))
		{
			$col = $this->segment('col');
			$name = $col->name();
			$route = $this->getRoute();

			$selected = $this->segment('selected');
			$current = $route->segment('filter');
			$current = (is_array($current))? $current:[];
			$currentName = (array_key_exists($name,$current))? $current[$name]:null;
			
			foreach ($array as $v => $label) 
			{
				if(is_scalar($label))
				{
					$r = [];
					$label = Base\Str::cast($label);
					$active = (in_array($v,$selected,true))? true:false;
					$filter = $current;
					
					$filter[$name] = [$v];
					$route = $route->changeSegments(['filter'=>$filter,'page'=>1]);
					$plus = null;
					$minus = null;
					
					if(!empty($current) && !empty($currentName))
					{
						$filter = $current;
						
						if(!array_key_exists($name,$filter) || !is_array($filter[$name]))
						$filter[$name] = [];
						
						if(in_array($v,$filter[$name],true) && $active === true)
						{
							$filter[$name] = Base\Arr::valueStrip($v,$filter[$name]);
							$minus = $route->changeSegments(['filter'=>$filter,'page'=>1]);
						}
						
						else
						{
							$filter[$name][] = $v;
							$plus = $route->changeSegments(['filter'=>$filter,'page'=>1]);
						}
					}
					
					$r['label'] = $label;
					$r['active'] = $active;
					$r['route'] = $route;
					$r['plus'] = $plus;
					$r['minus'] = $minus;
					$return[$v] = $r;
				}
			}
		}
		
		return $return;
	}
	
	
	// makeResults
	// génère les résultats d'affichage de la relation
	protected function makeResults(array $array,$attr=null,bool $loadMore=false):string 
	{
		$r = '';
		$routes = $this->makeRoutes($array);
		$col = $this->segment('col');
		$excerpt = $col->attr('excerpt');
		
		if(!empty($routes))
		{
			$r .= Html::ulOp($attr);
			
			foreach ($routes as $key => $value) 
			{
				if(is_array($value) && Base\Arr::keysAre(['label','active','route','plus','minus'],$value))
				{
					$label = $value['label'];
					$selected = $value['active'];
					$route = $value['route'];
					$plus = $value['plus'];
					$minus = $value['minus'];
					
					if(is_string($label) && strlen($label) && $route instanceof Core\Route && is_bool($selected))
					{
						$class = ($selected === true)? 'selected':null;
						
						if(is_int($excerpt))
						$label = Base\Str::excerpt($excerpt,$label);
						
						$value = $route->a($label,[$class,'replace']);
						
						if(!empty($plus))
						$value .= $plus->a(null,['icon','plus']);
						
						elseif(!empty($minus))
						$value .= $minus->a(null,['icon','minus']);
						
						$attr = (!empty($plus) || !empty($minus))? 'hasIcon':null;
						$r .= Html::li($value,$attr);
					}
				}
			}
			
			if(!empty($r) && $loadMore === true)
			$r .= $this->loadMore();
			
			$r .= Html::ulCl();
		}
		
		return $r;
	}
	
	
	// makeFilter
	// construit un input filter
	public static function makeFilter(Core\Col $col,Core\Route $currentRoute,string $route,$filter,$class=null,$closeAttr=null,?string $label=null):string
	{
		$r = '';
		$html = '';
		$name = $col->name();
		$table = $col->table();
		$relation = $col->relation();
		$size = $relation->size();
		$active = false;
		$selected = null;
		$after = null;
		
		if(is_array($filter) && array_key_exists($name,$filter))
		{
			$active = true;
			$selected = $filter[$name];
			$class[] = 'filtering';
			$closeFilter = Base\Arr::unset($name,$filter);
			$closeRoute = $currentRoute->changeSegments(['filter'=>$closeFilter]);
			$after = $closeRoute->a(null,$closeAttr);
		}
		
		$route = $route::make(['table'=>$table,'col'=>$col,'selected'=>$selected]);
		$limit = $route->limit();
		$query = $route::getSearchQuery();
		$data = ['query'=>$query,'separator'=>static::getDefaultSegment(),'char'=>static::getReplaceSegment()];
		if($route->hasOrder())
		$route = $route->changeSegment('order',true);
		$data['href'] = $route;
		$order = $route->orderSelect();
		
		if($size > $limit)
		{
			$searchMinLength = $table->searchMinLength();
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
		
		elseif($size > 1 && !empty($order))
		{
			$html .= Html::divOp('top');
			$html .= $route->orderSelect();
			$html .= Html::divCl();
		}
		
		$html .= Html::div(null,'result');
		$r .= Html::clickOpen($html,$label,$after,[$class,'data'=>$data]);
		
		return $r;
	}
}
?>
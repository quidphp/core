<?php
declare(strict_types=1);
namespace Quid\Core\Route;
use Quid\Base\Html;
use Quid\Core;
use Quid\Base;

// _general
trait _general
{
	// trait
	use _generalSegment, _generalPager;
	
	
	// config
	public static $configGeneral = array(
		'group'=>'general',
		'maxPerPage'=>100,
		'query'=>array('s')
	);
	
	
	// selectedUri
	// gère les selected uri pour une route general
	// par défaut la route avec segment par défaut est sélectionné
	public function selectedUri():array
	{
		$return = array();
		$route = static::make();
		$uri = $route->uri(null,array('query'=>false));
		$return[$uri] = true;
		
		return $return;
	}
	
	
	// makeGeneral
	// cette méthode permet de retourner une route general à partir de la classe
	public static function makeGeneral($navKey=null,$segment=null):?self
	{
		$return = null;
		$class = static::class;
		
		$key = array($class);
		if($navKey !== null)
		$key = Base\Arr::append($key,$navKey);
		
		$route = static::session()->nav()->route($key);
		
		if(empty($route))
		$route = $class::make($segment);
		
		if($route->isValidSegment())
		$return = $route;
		
		return $return;
	}
	
	
	// canReset
	// retourne vrai si la bouton reset peut s'afficher
	protected function canReset(?string $search=null,$not=null):bool 
	{
		$return = false;
		$default = static::getDefaultSegment();
		
		if(is_string($search))
		$return = true;
		
		else
		{
			$not = (array) $not;
			$segments = $this->segment(null,true);
			$segments = Base\Arr::keysStrip($not,$segments);

			if(array_key_exists('page',$segments) && $segments['page'] === '1')
			$segments['page'] = $default;
			
			foreach ($segments as $key => $value) 
			{
				if($value !== $default)
				{
					$return = true;
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// makeCount
	// fait le count pour une page general
	protected function makeCount():string 
	{
		$r = '';
		$sql = $this->sql();

		$table = $this->table();
		$where = $table->where();
		
		if(!empty($where))
		{
			$newSql = $sql->clone()->unset('where');
			$newSql->wheresOne($where);
			$tableCount = $newSql->triggerWhatCount(false);
		}
		
		else
		$tableCount = $sql->triggerTableCount(true);
		
		$whatCount = $sql->triggerWhatCount();
		
		if($tableCount === $whatCount)
		{
			$r .= $tableCount;
			$r .= " ";
			$r .= static::langPlural($tableCount,'lcf|common/row');
		}
		
		else
		{
			$r .= $whatCount;
			$r .= " ";
			$r .= static::langPlural($whatCount,'lcf|common/row');
			$r .= " ";
			$r .= static::langPlural($whatCount,'lcf|common/filtered');
			$r .= ", ";
			$r .= $tableCount;
			$r .= " ";
			$r .= static::langPlural($tableCount,'lcf|common/row');
			$r .= " ";
			$r .= static::langText('lcf|common/total');
		}
		
		return $r;
	}
	
	
	// makeSearch
	// construit le input search
	protected function makeSearch(?string $placeholder=null,?array $attr=null):string 
	{
		$r = '';
		$table = $this->table();
		
		if($table->isSearchable() && $this->hasPermission('search') && !empty($this->sql()->triggerTableCount()))
		{
			$cols = $table->cols()->searchable();
			
			if($cols->isNotEmpty())
			{
				$searchQuery = $this->getSearchQuery();
				$search = $this->getSearchValue();
				$searchMinLength = $table->searchMinLength();
				
				$uri = Base\Uri::removeQuery($this->changeSegments(array('page'=>1))->uri());
				$data = array('href'=>$uri,'char'=>$searchQuery,'keyupDelay'=>800,'current'=>$search,'pattern'=>array('minLength'=>$searchMinLength));

				$r .= Html::divOp('form');
				$r .= Html::divOp('input');
				$r .= Html::inputText($search,array('name'=>true,'placeholder'=>$placeholder,'data'=>$data));
				
				if($search !== null)
				$r .= Html::a($uri,null,Base\Arr::append($attr['close'] ?? null,'close'));
				
				$r .= Html::divCl();
				$r .= Html::button(null,Base\Arr::append($attr['search'] ?? null,'search'));
				$r .= Html::divCl();
			}
		}
		
		return $r;
	}
	
	
	// makeInputLimit
	// construit le input limit
	protected function makeInputLimit():string 
	{
		$r = '';
		$sql = $this->sql();
		
		if($this->hasPermission('limit','perPage') && $sql->isTriggerCountNotEmpty())
		{
			$route = $this->changeSegments(array('limit'=>true,'page'=>1));
			$limit = $sql->getLimit();
			$maxPerPage = static::$config['maxPerPage'];
			
			$data = array('href'=>$route,'char'=>static::getReplaceSegment(),'current'=>$limit,'keyupDelay'=>800,'pattern'=>'numberWholeNotEmpty','max'=>$maxPerPage);
			$r .= Html::divOp('limit');
			$r .= Html::inputText($limit,array('name'=>"limit",'data'=>$data));
			$r .= Html::span(static::langText('lcf|common/limit'));
			$r .= Html::divCl();
		}
		
		return $r;
	}
	
	
	// makePageInput
	// construit le input page
	protected function makePageInput(?array $attr=null):string 
	{
		$r = '';
		
		if($this->hasPermission('page'))
		{
			$sql = $this->sql();
			$general = $sql->general();
			
			if(!empty($general) && $general['total'] > 1)
			{
				if(!empty($general['prev']))
				{
					$route = $this->changeSegment('page',$general['prev']);
					$r .= $route->a(null,Base\Arr::append($attr,'prev'));
				}
				
				$route = $this->changeSegment('page',true);
				$total = $general['total'];
				$data = array('href'=>$route,'char'=>static::getReplaceSegment(),'current'=>$general['current'],'keyupDelay'=>800,'pattern'=>'numberWholeNotEmpty','max'=>$total);
				
				$r .= Html::divOp('center');
				$r .= Html::span(static::langText('common/page'));
				$r .= Html::inputText($general['current'],array('name'=>'page','data'=>$data));
				$r .= Html::span(static::langText('lcf|common/on')." ".$total);
				$r .= Html::divCl();
				
				if(!empty($general['next']))
				{
					$route = $this->changeSegment('page',$general['next']);
					$r .= $route->a(null,Base\Arr::append($attr,'next'));
				}
			}
		}
		
		return $r;
	}
	
	
	// makeGeneralNav
	// construit un block de navigation par page
	protected function makeGeneralNav(bool $firstLast=true,bool $prevNext=true,int $amount=3,bool $str=false)
	{
		$return = null;
		
		if($this->hasPermission('page'))
		{
			$sql = $this->sql();
			$general = $sql->general(null,$amount);
			
			if(!empty($general))
			$return = $this->makeGeneralPager($general,$firstLast,$prevNext,$str);
		}
		
		return $return;
	}
	
	
	// makeFilter
	// construit un input filter à partir d'une route generalRelation
	protected function makeFilter(Core\Col $col,string $route,$class,$closeAttr=null,?string $label=null):string
	{
		$r = '';
		
		if($this->hasPermission('filter'))
		{
			$filter = $this->segment('filter');
			$r .= $route::makeFilter($col,$this,$route,$filter,$class,$closeAttr,$label);
		}
		
		return $r;
	}
	
	
	// makeTableHeaderOrder
	// génère un lien pour ordonner dans un header de table
	protected function makeTableHeaderOrder(Core\Col $col,array $array,$attr=null,$icon=null):array
	{
		$html = $array[0];
		$thAttr = $array[1];
		
		if($this->hasPermission('order'))
		{
			$thAttr[] = 'orderable';
			$active = ($col === $this->segment('order'));
			$defaultDirection = strtolower(Base\Sql::invertOrderDirection($this->segment('direction')));
			
			if($active === true)
			{
				$direction = $defaultDirection;
				$thAttr[] = $direction;
				$thAttr[] = 'ordering';
			}
			
			else
			$direction = $col->direction();
			
			$route = $this->changeSegments(array('order'=>$col,'direction'=>$direction));
			$uri = $route->uri();
			$html .= Html::divOp('right');
			$html .= Html::span(null,$icon);
			$html .= Html::divCl();
			$html = Html::a($uri,$html,$attr);
		}
		
		return array($html,$thAttr);
	}
}
?>
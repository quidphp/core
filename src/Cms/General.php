<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Base\Html;
use Quid\Core;
use Quid\Base;

// general
class General extends Core\RouteAlias
{
	// trait
	use _templateAlias, Core\Route\_general, Core\Segment\_table, Core\Segment\_page, Core\Segment\_limit, Core\Segment\_order, Core\Segment\_direction, Core\Segment\_cols, Core\Segment\_filter, Core\Segment\_primaries;
	
	
	// config
	public static $config = [
		'path'=>[
			'en'=>'table/[table]/[page]/[limit]/[order]/[direction]/[cols]/[filter]/[in]/[notIn]/[highlight]',
			'fr'=>'table/[table]/[page]/[limit]/[order]/[direction]/[cols]/[filter]/[in]/[notIn]/[highlight]'],
		'segment'=>[
			'table'=>'structureSegmentTable',
			'page'=>'structureSegmentPage',
			'limit'=>'structureSegmentLimit',
			'order'=>'structureSegmentOrder',
			'direction'=>'structureSegmentDirection',
			'cols'=>'structureSegmentCols',
			'filter'=>'structureSegmentFilter',
			'in'=>'structureSegmentPrimaries',
			'notIn'=>'structureSegmentPrimaries',
			'highlight'=>'structureSegmentPrimaries'],	
		'match'=>[
			'role'=>['>='=>20]],
		'sitemap'=>true
	];
	
	
	// onBefore
	// validation avant le lancement de la route
	protected function onBefore()
	{
		$return = false;
		
		if($this->hasPermission('view'))
		{
			$table = $this->table();
			$sql = $this->sql();
			$nav = $this->session()->nav();
			$nav->set([static::class,$table],$this->uriRelative());
			$return = true;
		}
		
		return $return;
	}
	
	
	// fallback
	// sur fallback, efface la version de la route dans nav/session
	protected function fallback($context=null) 
	{
		if($this->hasTable())
		{
			$table = $this->table();
			$nav = $this->session()->nav();
			$nav->unset([static::class,$table]);
		}
		
		return parent::fallback($context);
	}
	
	
	// onReplace
	// change le titre et la metaDescription pour la table
	protected function onReplace(array $return):array 
	{
		$return['title'] = $this->title();
		$return['metaDescription'] = $this->table()->description();
		
		return $return;
	}
	
	
	// selectedUri
	// génère une les uri sélectionnés pour une route en lien avec une table
	public function selectedUri():array
	{
		$return = [];
		$tables = $this->db()->tables();
		$table = $this->table();
		$session = $this->session();
		
		$root = static::session()->routeTableGeneral($table);
		$uri = $root->uri();
		$return[$uri] = true;
		
		return $return;
	}
	
	
	// hasTable
	// retouren vrai si la route est lié à une table
	public function hasTable():bool 
	{
		return ($this->segment('table') instanceof Core\Table)? true:false;
	}
	
	
	// table
	// retourne l'objet table
	public function table():Core\Table
	{
		return $this->cache(__METHOD__,function() {
			$return = $this->segment('table');
			
			if(is_string($return))
			$return = static::db()->table($return);
			
			return $return;
		});
	}
	
	
	// generalSegments
	// retourne les segments à utiliser pour la création de l'objet sql
	protected function generalSegments():array 
	{
		return $this->segment(['page','limit','order','direction','filter','in','notIn']);
	}
	
	
	// getCurrentCols
	// retourne les colonnes courantes
	protected function getCurrentCols():Core\Cols 
	{
		$return = null;
		
		if($this->hasSegment('cols'))
		$return = $this->segment('cols');
		
		else
		{
			$table = $this->table();
			$return = $table->cols()->general()->filter(['isVisibleGeneral'=>true]);
		}
		
		return $return;
	}
	
	
	// getHighlight
	// retourne les lignes highlight
	protected function getHighlight():?array 
	{
		$return = null;
		
		if($this->hasSegment('highlight'))
		$return = $this->segment('highlight');
		
		return $return;
	}
	
	
	// makeViewTitle
	// génère le titre spécifique de general pour la table, si existant
	protected function makeViewTitle(?string $lang=null):?string
	{
		$r = null;
		$table = $this->table();
		$r = $this->lang()->safe(['table','view',$table],null,$lang);
		
		return $r;
	}
	
	
	// makeTitle
	// retourne le titre de la route
	protected function makeTitle(?string $lang=null):string
	{
		$r = $this->makeViewTitle($lang);
		
		if(empty($r))
		$r = $this->table()->label(null,$lang);
		
		return $r;
	}
	
	
	// allSegment
	// retourne tous les segments de la route, un par table
	public static function allSegment():array
	{
		$return = [];
		$db = static::db();
		
		foreach ($db->tables() as $table) 
		{
			if($table->hasPermission('view'))
			$return[] = ['table'=>$table];
		}
		
		return $return;
	}
	
		
	// main
	// génère le html de main pour general
	public function main():string
	{
		$r = $this->makeTop();
		$r .= $this->makeTable();
		
		return $r;
	}
	
	
	// makeTop
	// génère la partie supérieur de la page
	protected function makeTop():string 
	{
		$r = '';
		$table = $this->table();
		
		$r .= Html::divOp("top");
		
		$r .= Html::divOp("left");
		
		$r .= Html::divOp("title");
		$r .= Html::h1($table->label());
		$r .= $this->makeInfo();
		$r .= Html::divCl();
		
		if($this->hasPermission('description'))
		$r .= Html::divCond($table->description(),['description','subTitle']);
		
		$placeholder = static::langText('general/search');
		$r .= Html::divOp('search');
		$r .= $this->makeSearch($placeholder,['close'=>['icon','solo'],'search'=>['icon','solo']]);
		$r .= $this->makeSearchNote();
		$r .= Html::divCl();
		
		$r .= Html::divCl();
		
		$r .= Html::divOp("right");
		$r .= Html::divCond($this->makeOperation(),'operation');
		$r .= $this->makeInputLimit();
		$r .= Html::divCl();
		
		$r .= Html::divCl();
		
		return $r;
	}
	
	
	// makeSearchNote
	// génère les notes pour le champ recherche
	protected function makeSearchNote():string 
	{
		$r = '';
		$table = $this->table();
		
		if($table->isSearchable() && $this->hasPermission('searchNote'))
		{
			$cols = $table->cols()->searchable();
			
			if($cols->isNotEmpty())
			{
				$r .= Html::divOp('in');
				$r .= Html::divOp('first');
				$r .= Html::span(static::langText('general/note').":");
				$r .= Html::span(static::langText('general/searchNote'),'note');
				$r .= Html::divCl();
				$r .= Html::divOp('second');
				$r .= Html::span(static::langText('general/searchIn').":");
				$r .= Html::span(implode(', ',$cols->pair('label')),'labels');
				$r .= Html::divCl();
				$r .= Html::divCl();
			}
		}
		
		return $r;
	}
	
	
	// makeOperation
	// génère le bloc des opérations
	protected function makeOperation():string 
	{
		$r = '';
		$table = $this->table();
		$callback = $table->attr('generalOperation');
		
		if(static::classIsCallable($callback))
		$r .= $callback($table);
		
		$r .= $this->makeReset();
		$r .= $this->makeTruncate();
		$r .= $this->makeExport();
		$r .= $this->makeAdd();
		
		return $r;
	}
	
	
	// makeReset
	// génération le lien de réinitialisation
	protected function makeReset():string 
	{
		$r = '';
		
		if($this->hasPermission('reset') && $this->canReset($this->getSearchValue(),'table'))
		{
			$option = ['query'=>false];
			$route = $this->keepSegments('table');
			$r .= $route->a(static::langText('general/reset'),['submit','reset','icon','padLeft'],null,$option);
		}
		
		return $r;
	}
	
	
	// makeInfo
	// génère le block d'informations
	protected function makeInfo():string 
	{
		$r = '';
		
		if($this->hasPermission('info'))
		{
			$popup = $this->makeInfoPopup();
			$attr = ['countInfo',(!empty($popup))? ['withPopup','anchorCorner']:null];
			
			$r .= Html::divOp($attr);
			$r .= Html::div($this->makeCount(),['count','icon','info','padLeft']);
			$r .= $popup;
			$r .= Html::divCl();
		}
		
		return $r;
	}
	
	
	// makeInfoPopup
	// génère le popup d'informations
	// la méthode est public car utilisé dans specific
	public function makeInfoPopup(bool $icon=false):string 
	{
		$r = '';
		
		if($this->hasPermission('infoPopup'))
		{
			$table = $this->table();
			$sql = $this->sql();
			$loop = array_keys($this->segment());
			$loop = Base\Arr::append($loop,['search','primary','engine','collation','autoIncrement','classTable','classRow','classRows','classCols','classCells','sql']);
			$r .= Html::divOp('popup');
			
			if($icon === true)
			$r .= Html::div(null,['icon','topRight','solo','info']);
			
			$r .= Html::ulOp();
			
			foreach ($loop as $key) 
			{
				$label = null;
				$value = ($this->hasSegment($key))? $this->segment($key):null;
				$lang = $this->lang();
				
				if($key === 'table')
				$value = $table->name();
				
				elseif($key === 'order' && $value instanceof Core\Col)
				$value = $value->name();
				
				elseif($key === 'direction' && is_string($value))
				$value = static::langText('direction/'.strtolower($value));
				
				elseif($key === 'cols' && $value instanceof Core\Cols)
				{
					$value = $value->names();
					$label = static::langPlural($value,'general/'.$key);
					$value = implode(', ',$value);
				}
				
				elseif(in_array($key,['in','notIn','highlight'],true) && is_array($value))
				$value = implode(', ',$value);
				
				elseif($key === 'search')
				$value = $this->getSearchValue();
				
				elseif($key === 'sql')
				$value = $sql->emulate();
				
				elseif(in_array($key,['engine','primary','collation'],true))
				$value = $table->$key();
				
				elseif($key === 'autoIncrement')
				$value = $table->autoIncrement(true);
				
				elseif($key === 'classTable')
				$value = $table->classFqcn();
				
				elseif($key === 'classRow')
				$value = $table->classe()->row();
				
				elseif($key === 'classRows')
				$value = $table->classe()->rows();
				
				elseif($key === 'classCols')
				$value = $table->classe()->cols();
				
				elseif($key === 'classCells')
				$value = $table->classe()->cells();
				
				elseif($key === 'filter')
				{
					if(is_array($value) && !empty($value))
					{
						$filter = $value;
						$value = [];
						
						foreach ($filter as $k => $v) 
						{
							$col = $table->col($k);
							$rel = $col->relation()->getStr($v,', ');
							$value[] = $col->label().": ".$rel;
						}
					}
				}
				
				if(!empty($value))
				{
					if(empty($label))
					$label = $lang->alt('general/'.$key,'common/'.$key);
					
					$r .= Html::liOp();
					$r .= Html::span($label.":");
					
					if(is_array($value))
					{
						$lis = Html::liMany(...$value);
						$r .= Html::ulCond($lis);
					}
					
					else
					$r .= Html::span($value);
					
					$r .= Html::liCl();
				}
			}

			$r .= Html::ulCl();
			$r .= Html::divCl();
		}
		
		return $r;
	}
	

	// makeAdd
	// génère le lien pour ajouter une nouvelle ligne
	protected function makeAdd():string 
	{
		$r = '';
		$table = $this->table();
		
		if($this->hasPermission('insert','add'))
		$r .= SpecificAdd::makeOverload($table)->a(static::langText('common/add'),['submit','icon','padLeft','add']);

		return $r;
	}
	
	
	// makeExport
	// génère le lien pour exporter la table
	protected function makeExport():string 
	{
		$r = '';
		$sql = $this->sql();
		
		if($this->hasPermission('export') && !$sql->isTriggerCountEmpty())
		{
			$segment = $this->segment();
			$route = GeneralExportDialog::makeOverload($segment);
			$r .= $route->aDialog();
		}
		
		return $r;
	}
	
	
	// makeTruncate
	// génère le formulaire pour effacer toute la table
	protected function makeTruncate():string 
	{
		$r = '';
		$table = $this->table();
		
		if($this->hasPermission('truncate','empty') && !empty($table->rowsCount(true,true)))
		{
			$data = ['confirm'=>static::langText('common/confirm')];
			$route = GeneralTruncate::makeOverload($table);
			
			$r .= Html::divOp('truncate');
			$r .= $route->formOpen(['data'=>$data]);
			$r .= $this->tableHiddenInput();
			$r .= Html::submit($route->label(),['icon','truncate','padLeft']);
			$r .= Html::formClose();
			$r .= Html::divCl();
		}
		
		return $r;
	}
	
	
	// makeTool
	// génère le block outil
	protected function makeTool():string 
	{
		$r = '';
		
		if($this->hasPermission('rows'))
		{
			$r .= Html::divOp('tool');
			$char = static::getReplaceSegment();
			$defaultSegment = static::getDefaultSegment();
			
			if($this->hasPermission('in'))
			{
				$route = $this->changeSegments(['page'=>1,'in'=>true]);
				$data = ['href'=>$route,'char'=>$char,'separator'=>$defaultSegment];
				$r .= Html::div(null,['icon','solo','in','data'=>$data]);
			}
			
			if($this->hasPermission('notIn'))
			{
				$notIn = $this->segment('notIn');
				$notIn[] = $char;
				$route = $this->changeSegments(['page'=>1,'notIn'=>$notIn]);
				$data = ['href'=>$route,'char'=>$char,'separator'=>$defaultSegment];
				$r .= Html::div(null,['icon','solo','notIn','data'=>$data]);
			}
			
			$r .= $this->makeGeneralDelete();
			$r .= Html::divCl();
		}
		
		return $r;
	}
	
	
	// makeGeneralDelete
	// génère le formulaire pour effacer de multiples lignes
	protected function makeGeneralDelete():string 
	{
		$r = '';
		
		if($this->hasPermission('remove','delete','multiDelete'))
		{
			$table = $this->table();
			$route = GeneralDelete::makeOverload(['table'=>$table,'primaries'=>true]);
			$defaultSegment = static::getDefaultSegment();
			$data = ['confirm'=>static::langText('common/confirm'),'separator'=>$defaultSegment];

			$r .= Html::divOp('multiDelete');
			$r .= $route->formOpen(['data'=>$data]);
			$r .= $this->tableHiddenInput();
			$r .= Html::inputHidden(null,'primaries');
			$r .= Html::submit(' ',['icon','solo','multiDelete']);
			$r .= Html::formCl();
			$r .= Html::divCl();
		}
		
		return $r;
	}
	
	
	// makeCols
	// génère le popup pour choisir les colonnes à afficher dans la table
	protected function makeCols():string 
	{
		$r = '';
		$table = $this->table();
		$cols = $table->cols();
		$currentCols = $this->getCurrentCols();
		$inAttr = ['in'];
		
		if($this->hasPermission('view','cols') && $cols->isNotEmpty() && $currentCols->isNotEmpty())
		{
			$defaultSegment = static::getDefaultSegment();
			$route = $this->changeSegment('cols',true);
			$current = implode($defaultSegment,$currentCols->names());
			$data = ['href'=>$route,'char'=>static::getReplaceSegment(),'current'=>$current,'separator'=>$defaultSegment];
			$inAttr[] = 'anchorCorner';
			$session = static::session();
			
			$checkbox = [];
			$attr = ['name'=>'col','data-required'=>true,];
			$option = ['value'=>[],'html'=>['div','col']];
			foreach ($cols as $key => $value) 
			{
				if($value->isVisibleGeneral(null,null,$session))
				{
					$checkbox[$key] = $value->label();
					
					if($currentCols->exists($key))
					$option['value'][] = $key;
				}
			}
			
			$r .= Html::div(null,['icon','solo','cols','center']);
			
			$r .= Html::divOp('popup');
			$r .= Html::divOp('inside');
			$r .= Html::checkbox($checkbox,$attr,$option);
			$r .= Html::button(null,['name'=>'cols','icon','check','solo','topRight','data'=>$data]);
			$r .= Html::divCl();
			$r .= Html::divCl();
		}
		
		$r = Html::div($r,$inAttr);
		
		return $r;
	}
	
	
	// makeTable
	// génère la table
	protected function makeTable():string 
	{
		$r = '';
		
		if($this->hasPermission('view'))
		{
			$table = $this->table();
			$cols = $this->getCurrentCols();
			$sql = $this->sql();
			
			$tableIsEmpty = $table->isRowsEmpty(true);
			$isEmpty = $sql->isTriggerCountEmpty();
			$class = ($isEmpty === true)? 'empty':'notEmpty'; 
			
			$r .= Html::divOp(['container',$class]);
			
			if($isEmpty === true || $cols->isEmpty())
			{
				$r .= Html::divOp('notFound');
				$r .= Html::h3(static::langText('general/notFound'));
				$r .= Html::divCl();
			}
			
			if($cols->isNotEmpty() && $tableIsEmpty === false)
			{
				if($isEmpty === false)
				{
					$page = Html::divCond($this->makePageInput(['icon','solo','black']),'pageInput');
					
					$r .= Html::divOp('above');
					$r .= Html::div($this->makeTool(),'left');
					$r .= Html::divCond($page,'right');
					$r .= Html::divCl();
				}
				
				$r .= Html::divOp('scroller');
				$r .= Html::tableOpen();
				$r .= $this->makeTableHeader();
				$r .= $this->makeTableBody();
				$r .= Html::tableCl();
				$r .= Html::divCl();
				
				if($isEmpty === false)
				{
					$r .= Html::divOp('bellow');
					$r .= Html::divCond($page,'right');
					$r .= Html::divCl();
				}
			}
			
			$r .= Html::divCl();
		}
		
		return $r;
	}
	
	
	// makeTableHeader
	// génère le header de la table
	protected function makeTableHeader():string 
	{
		$r = '';
		$table = $this->table();
		$cols = $this->getCurrentCols();
		
		if($cols->isNotEmpty())
		{
			$ths = [];
			$permission['filter'] = $this->hasPermission('filter');
			$permission['order'] = $this->hasPermission('order','direction');
			$count = $cols->count();
			
			if($this->hasPermission('rows'))
			{
				$html = Html::div(null,['icon','solo','check','center']);
				$html .= Html::div(null,['icon','solo','uncheck','center']);
				$html = Html::div($html,['in','toggleAll']);
				$ths[] = [$html,'rows'];
			}
			
			foreach ($cols as $col) 
			{
				$thAttr = [];
				
				if($col->isPrimary())
				$thAttr[] = 'primary';
				
				$label = Html::span($col->label(),'label');
				$in = Html::divtable($label);
				
				if($permission['order'] === true && $col->isOrderable())
				{
					$icon = ['icon','solo','arrow','white'];
					$array = $this->makeTableHeaderOrder($col,[$in,$thAttr],'in',$icon);
				}
				
				else
				{
					$in = Html::div($in,'in');
					$array = [$in,$thAttr];
				}
				
				if($permission['filter'] === true && $col->isFilterable() && $col->relation()->size() > 0)
				$array = $this->makeTableHeaderFilter($col,$array);
				
				$ths[] = $array;
			}
			
			if($this->hasPermission('action'))
			{
				$html = $this->makeCols();
				$ths[] = [$html,'action'];
			}
			
			$r = Html::thead($ths);
		}
		
		return $r;
	}
	
	
	// makeTableHeaderFilter
	// génère un filtre dans un header de table
	protected function makeTableHeaderFilter(Core\Col $col,array $array):array
	{
		$html = $array[0];
		$thAttr = $array[1];
		$thAttr[] = ['filterable'];
		
		$html .= Html::divOp('left');
		$class = ['filterOuter','clickOpen','anchorCorner'];
		$close = ['icon','solo','close'];
		$label = Html::div(null,['filter','icon','solo']);
		$html .= $this->makeFilter($col,GeneralRelation::class,$class,$close,$label);
		$html .= Html::divCl();
		
		return [$html,$thAttr];
	}
	
	
	// makeTableBody
	// génère le body de la table
	protected function makeTableBody():string 
	{
		$r = '';
		$cols = $this->getCurrentCols();
		$rows = $this->rows();
		$highlight = $this->getHighlight();
		
		if($cols->isNotEmpty() && $rows->isNotEmpty())
		{
			$table = $this->table();
			$trs = [];
			$rowsPermission = $this->hasPermission('rows');
			$actionPermission = $this->hasPermission('action');
			$modify = $this->hasPermission('modify','update');
			$specificPermission = $this->hasPermission('specific');
			
			foreach ($rows as $row) 
			{
				$array = [];
				$specific = Specific::makeOverload($row)->uri();
				$cells = $row->cells($cols);
				$rowClass = (!empty($highlight) && in_array($row->primary(),$highlight,true))? 'highlight':null;
				
				if($rowsPermission === true)
				{
					$checkbox = Html::inputCheckbox($row,'row');
					$label = Html::label($checkbox,['in']);
					$array[] = [$label,'rows'];
				}
				
				foreach ($cells as $cell) 
				{
					$option = ['specific'=>$specific];
					$array[] = $this->makeTableBodyCell($cell,$option);
				}
				
				if($actionPermission === true)
				{
					$html = '';
					$action = ($modify === true && $row->isUpdateable())? 'modify':'view';
					
					if($this->hasPermission($action) && $specificPermission === true)
					$html = Html::a($specific,Html::div(null,['icon','solo',$action,'center']),'in');
					
					$array[] = [$html,'action'];
				}
				
				$trs[] = [$array,$rowClass];
			}

			$r .= Html::tbody(...$trs);
		}
		
		return $r;
	}
	
	
	// makeTableBodyCell
	// génère le contenu à afficher dans une cellule de table
	protected function makeTableBodyCell(Core\Cell $cell,?array $option=null):array 
	{
		$r = [];
		$option = Base\Arr::plus(['specific'=>null,'modify'=>false,'excerptMin'=>$cell->generalExcerptMin()],$option);
		$context = static::context();
		$attr = null;
		
		$v = $cell->get($context);
		
		if($cell->isPrimary() && is_string($option['specific']))
		{
			$specific = $option['specific'];
			$v = Html::a($specific,$v,'in');
			$attr = 'primary';
		}
		
		else
		$v = Html::div($v,'in',$option);
		
		$r = [$v,$attr];
		
		return $r;
	}
}

// config
General::__config();
?>
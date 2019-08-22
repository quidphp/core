<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Base\Html;
use Quid\Core;

// home
class Home extends Core\Route\Home
{
	// trait
	use _templateAlias;
	
	
	// config
	public static $config = array(
		'match'=>array(
			'role'=>array('>='=>20))
	);
	
	
	// main
	// génère main pour home
	public function main():string
	{
		$r = Html::divCond($this->mainTop(),'top');
		$r .= Html::divCond($this->mainBottom(),'bottom');

		return $r;
	}
	
	
	// mainTop
	// génère la partie top de main
	protected function mainTop():string 
	{
		$r = '';
		
		$r .= Html::divCond($this->mainTopLeft(),'left');
		$r .= Html::divCond($this->mainTopRight(),'right');
		
		return $r;
	}
	
	
	// mainTopLeft
	// génère le html pour la partie en haut à gauche de la page d'accueil
	protected function mainTopLeft():string 
	{
		$r = '';
		$r .= Html::h1($this->makeTopTitle());
		
		if(static::sessionUser()->can('home/info'))
		$r .= $this->makeInfo();
		
		return $r;
	}
	
	
	// makeTopTitle
	protected function makeTopTitle():string 
	{
		return static::boot()->typeLabel();
	}
	
	
	// makeInfo
	// génère les informations en haut de la page
	protected function makeInfo():string 
	{
		$r = '';
		$tables = $this->db()->tables();
		$total = $tables->filter(array('hasPermission'=>true),'view')->total(true,true);
		$popup = $this->makeInfoPopup();;
		$attr = array('countInfo',(!empty($popup))? array('withPopup','anchorCorner'):null);
		$r .= Html::divOp($attr);
		$r .= Html::divOp(array('count','icon','info','padLeft'));
		$r .= Html::span($total['table']." ".static::langPlural($total['table'],'lcf|common/table'));
		$r .= Html::span(",&nbsp;");
		$r .= Html::span($total['row']." ".static::langPlural($total['row'],'lcf|common/row'));
		$r .= Html::span("&nbsp;".static::langText('lcf|common/and')."&nbsp;");
		$r .= Html::span($total['col']." ".static::langPlural($total['col'],'lcf|common/col'));
		$r .= Html::divCl();
		$r .= $popup;
		$r .= Html::divCl();
		
		return $r;
	}
	
	
	// makeInfoPopup
	// génère le popup d'informations
	protected function makeInfoPopup():string 
	{
		$r = '';
		
		if(static::sessionUser()->can('home/infoPopup'))
		{
			$db = $this->db();
			$loop = array('dbName','driver','serverVersion','host','username','charset','collation','connectionStatus','classDb','classTables');
			
			$r .= Html::divOp('popup');
			$r .= Html::ulOp();
			
			foreach ($loop as $v) 
			{
				$label = static::langText('home/'.$v);
				
				if($v === 'classDb')
				$value = $db->classFqcn();
				
				elseif($v === 'classTables')
				$value = $db->tables()->classFqcn();
				
				else
				$value = $db->$v();
				
				if(!empty($value))
				{
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
	
	
	// mainTopRight
	// génère le html pour la partie en haut à droite de la page d'accueil
	protected function mainTopRight():string 
	{
		$r = '';
		$r .= $this->makeAbout();
		
		return $r;
	}
	
	
	// makeAbout
	// bouton vers la page à propos
	protected function makeAbout():string 
	{
		$r = '';
		$session = static::session();
		
		if($session->can('about'))
		{
			$route = About::makeOverload();
			$r .= $route->aDialog(array('submit','help','icon','padLeft'));
		}
		
		return $r;
	}
	
	
	// mainBottom
	// génère la partie bottom de main
	protected function mainBottom():string 
	{
		$r = '';
		$r .= Html::divCond($this->makeTask(),'task');
		
		$r .= Html::divOp('search');
		$r .= Html::divtableOpen();
		$r .= $this->makeSearch();
		$r .= Html::divtableClose();
		$r .= Html::divCl();
		
		return $r;
	}
	
	
	// makeTask
	// génère les tâches, simples liens qui apparaissent en haut de la page home
	protected function makeTask():string 
	{
		$r = '';
		$tables = $this->db()->tables();
		
		foreach ($tables->filter(array('attrNotEmpty'=>true),'homeTask') as $table) 
		{
			if($table->hasPermission('view','insert','add'))
			$r .= SpecificAdd::makeOverload($table)->aTitle();
		}
		
		return $r;
	}
	
	
	// makeSearch
	// génère le champ de recherche globale
	public function makeSearch():string 
	{
		$r = '';
		
		if(static::sessionUser()->can('home/search'))
		{
			$route = HomeSearch::makeOverload();
			$tables = $this->db()->tables();
			$searchable = $route->searchable(false);
			
			if($searchable->isNotEmpty())
			{
				$searchMinLength = $tables->searchMinLength();
				$data = array('query'=>'s','required'=>true,'keyupDelay'=>800,'pattern'=>array('minLength'=>$searchMinLength));
				
				$r .= $route->formOpen();
				$r .= Html::inputText(null,array('name'=>true,'placeholder'=>static::langText('home/searchSubmit'),'data'=>$data));
				$r .= Html::submit(true,array('button','solo','icon','search'));
				$r .= Html::div(null,'popup');
				$r .= Html::formClose();
				
				$r .= Html::divOp('in');
				$r .= Html::divOp('first');
				$r .= Html::span(static::langText('home/note').":");
				$r .= Html::span(static::langText('home/searchNote'),'note');
				$r .= Html::divCl();
				$r .= Html::divOp('second');
				$r .= Html::span(static::langText('home/searchIn').":");
				$r .= Html::span(implode(', ',$searchable->pair('label')),'labels');
				$r .= Html::divCl();
				$r .= Html::divCl();
			}
		}
		
		return $r;
	}
}

// config
Home::__config();
?>
<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Base\Html;
use Quid\Base;

// _template
trait _template
{
	// trait
	use _common;
	
	
	// trigger
	// trigger pour toutes les pages html du cms
	public function trigger() 
	{
		$r = $this->docOpen();
		$r .= Html::div(null,'loadingIcon');
		$r .= Html::headerCond($this->header());
		
		$html = Html::mainOp();
		$html .= Html::div($this->main(),'inner');
		$html .= Html::mainCl();
		
		$html .= Html::footerCond($this->footer());
		$html .= Html::divCond($this->makeJsBox(),'jsBox');
		$html .= $this->docClose();
		
		$com = Html::divCond($this->makeCom(),'com');
		$r .= $com.$html;

		return $r;
	}
	
	
	// header
	// génère le header pour toutes les pages du cms
	protected function header():string 
	{ 
		$r = '';
		
		$r .= Html::divOp('top');
		$r .= Html::div(null,['burgerMenu','icon','burger','solo']);
		$r .= Html::divCond($this->headerLeft(),'left');
		$r .= Html::divCond($this->headerRight(),'right');
		$r .= Html::divCl();
		$r .= Html::navCond($this->nav());
		
		return $r;
	}
	
	
	// headerLeft
	// génère le header gauche pour toutes les pages du cms
	protected function headerLeft():string 
	{
		$r = '';
		$boot = static::boot();
		$route = Home::makeOverload();
		
		$r .= Html::divOp('bootLabel');
		$r .= $route->a($boot->label());
		$r .= Html::divCl();
		
		$r .= Html::div($boot->typeLabel(),'contextType');
		
		return $r;
	}
	
	
	// headerRight
	// génère le header droite pour toutes les pages du cms
	protected function headerRight():string 
	{
		$r = '';
		$user = static::sessionUser();
		
		if($user->isSomebody())
		{
			$username = $user->username();
			$dateLogin = $user->dateLogin();
			
			$r .= Html::divOp('top');
			
			if($user->can('account'))
			$r .= Account::makeOverload()->aTitle(null,['submit','icon','padLeft','account']);
			
			if($user->can('accountChangePassword'))
			$r .= AccountChangePassword::makeOverload()->aDialog(['submit','icon','padLeft','password']);
			
			if($user->can('logout'))
			$r .= Logout::makeOverload()->aTitle(null,['submit','icon','padLeft','logout']);
			
			$r .= Html::divCl();
			
			$r .= Html::divOp('info');
			$r .= Html::div($username,'username');
			$r .= Html::div("|",'separator');
			$r .= Html::div($dateLogin->label().": ".Base\Date::format(4,$dateLogin),'dateLogin');
			$r .= Html::divCl();
		}
		
		return $r;
	}
	
	
	// nav
	// génère la navigation principale pour toutes les pages du cms
	protected function nav():string 
	{
		$r = '';
		$tables = $this->db()->tables();
		$tables = $tables->hasPermission('view');
		$hierarchy = $tables->hierarchy(false);
		
		$r .= Html::ulCond($this->navMenu($hierarchy,0));
		
		return $r;
	}
	
	
	// navMenu
	// génère un niveau de menu pour la navigation principale
	protected function navMenu(array $array,int $i):string 
	{
		$r = '';
		$session = $this->session();
		$tables = $this->db()->tables();
		$lang = $this->lang();
		$routes = static::boot()->routes();
		$specificAdd = $routes->get(SpecificAdd::class);
		
		if(!empty($array))
		{
			$ii = $i + 1;
			
			foreach ($array as $key => $value) 
			{
				if(is_string($key) && !empty($key))
				{
					$table = $tables->get($key);
					
					if(is_array($value))
					{
						$class = ['sub','anchorCorner'];
						$keys = array_keys($value);
						
						if($this->isTableTop($keys))
						$class[] = 'top';
					}
					
					else
					$class = [];
					
					$r .= Html::liOp($class);
					
					if(!empty($table))
					{
						$route = static::session()->routeTableGeneral($table,true);
						$option = ($route->routeRequest()->isSegmentParsedFromValue())? ['query'=>false]:null;
						$r .= $route->aTitle(null,null,null,$option);
						
						if($i > 0 && !empty($specificAdd) && $table->hasPermission('navAdd','add','insert'))
						{
							$route = $specificAdd::makeOverload($table);
							$r .= $route->makeNavLink();
						}
					}
					
					else
					{
						$label = $lang->tableLabel($key);
						$r .= Html::span($label);
					}
					
					if(is_array($value))
					{
						$r .= Html::div(null,['arrow','white']);
						$r .= Html::ulCond($this->navMenu($value,$ii));
					}
					
					$r .= Html::liClose();
				}
			}
		}
		
		return $r;
	}
	
	
	// footer
	// génère le footer pour toutes les pages du cms
	protected function footer():string
	{
		$r = '';
		
		$r .= Html::div($this->footerLeft(),'left');
		$r .= Html::divCond($this->footerRight(),'right');
		
		return $r;
	}
	
	
	// footerLeft
	// génère la partie gauche du footer pour toutes les pages du cms
	protected function footerLeft():string 
	{
		$r = '';
		$links = $this->footerLinks();
		
		if(!empty($links))
		{
			foreach ($links as $link) 
			{
				if(is_string($link))
				$r .= Html::liCond($link);
			}
			
			$r = Html::ulCond($r);
			$r = Html::navCond($r);
		}
		
		return $r;
	}
	
	
	// footerLinks
	// retourne un tableau avec tous les liens à mettre dans la partie gauche du footer
	protected function footerLinks():array 
	{
		$return = [];
		$user = static::sessionUser();
		
		if($user->can('footerTypes'))
		$return = Base\Arr::append($return,$this->footerTypes());
		
		if($user->can('footerModules'))
		$return = Base\Arr::append($return,$this->footerModules());
		
		return $return;
	}
	
	
	// footerTypes
	// retourne un tableau avec les liens vers les différents types
	// n'inclut pas un lien vers le type courant ou le type du cms
	protected function footerTypes():array 
	{
		$return = [];
		$user = static::sessionUser();
		$boot = static::boot();
		$type = $boot->type();
		$schemeHosts = $boot->schemeHostTypes();
		$lang = $this->lang();
		
		foreach ($schemeHosts as $key => $uri) 
		{
			if($key === 'cms' && !$user->can('footerTypesCms'))
			continue;
			
			if($key !== $type)
			{
				$label = $lang->typeLabel($key);
				$return[] = Html::a($uri,$label);
			}
		}
		
		return $return;
	}
	
	
	// footerModules
	// retourne un tableau avec les liens pour les modules
	protected function footerModules():array
	{
		$return = [];
		$routes = static::boot()->routesActive();
		$modules = $routes->filter(['group'=>'cms/module']);
		
		if($modules->isNotEmpty())
		{
			foreach ($modules as $module) 
			{
				$return[] = $module::makeOverload()->aTitle();
			}
		}
		
		return $return;
	}
	

	// footerRight
	// génère la partie droite du footer pour toutes les pages du cms
	protected function footerRight():string 
	{
		$r = '';
		$version = static::boot()->version(true,true,true);
		$author = $this->authorLink();
		$copyright = static::langText('footer/copyright',['version'=>$version]);
		
		$r .= Html::span($author,'author');
		$r .= Html::span('|','separator');
		$r .= Html::span($copyright,'copyright');
		
		return $r;
	}
	
	
	// makeJsBox
	// génère le html pour le jsBox
	protected function makeJsBox():string 
	{
		$r = Html::divOp('background');
		$r .= Html::divOp('outer');
		$r .= Html::divOp('box');
		$r .= Html::div(null,['icon','solo','close']);
		$r .= Html::divOp('inner');
		$r .= Html::divCl();
		$r .= Html::divCl();
		$r .= Html::divCl();
		$r .= Html::divCl();
		
		return $r;
	}
	
	
	// makeCom
	// génère le block pour la communication
	protected function makeCom():string 
	{
		$r = '';
		$com = static::sessionCom();
		$comText = $com->flush();

		if(!empty($comText))
		{
			$route = Specific::makeOverload(true);
			$data = ['href'=>$route,'char'=>$route::getReplaceSegment()];
			
			$r .= Html::divOp(['box','data'=>$data]);
			$r .= Html::div(null,['icon','solo','close']);
			$r .= Html::divOp('top');
			$r .= Html::div(null,['arrow','black']);
			$r .= Html::div(Base\Date::format(4),'date');
			$r .= Html::divCl();
			$r .= Html::div(null,'spacer');
			$r .= Html::div($comText,'bottom');
			$r .= Html::divCl();
		}
		
		return $r;
	}
}
?>
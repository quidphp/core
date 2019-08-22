<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Routing;
use Quid\Main;
use Quid\Base;

// route
abstract class Route extends Routing\Route
{
	// trait
	use _fullAccess;
	

	// config
	public static $config = [ // config pour la route
		'type'=>null, // type de la route
		'metaTitle'=>['bootLabel'=>true,'typeLabel'=>false], // éléments à ajouter à la fin du titre
		'jsInit'=>true, // s'il faut init le js en cas de requête non ajax
		'label'=>null, // nom de la route
		'description'=>null, // description de la route
		'row'=>null, // permet de spécifier la classe row en lien avec la route
		'docOpen'=>[
			'html'=>['data-route'=>'%name%'],
			'head'=>[
				'css'=>[
					'type'=>'css/%type%.css'],
				'js'=>[
					'jquery'=>'js/jquery/jquery.js',
					'base'=>'js/include.js',
					'type'=>'js/%type%.js']],
			'wrapper'=>["#wrapper"]],
		'@cms'=>[ // config pour cms
			'metaTitle'=>['typeLabel'=>true],
			'docOpen'=>[
				'head'=>[
					'js'=>[
						'jquery-ui'=>'js/jquery/jquery-ui.js']]]]
	]; 
	
	
	// getTimeoutObject
	// retourne l'objet timeout
	public static function getTimeoutObject():Main\Timeout 
	{
		return static::session()->timeout();
	}
	

	// onPrepareDoc
	// prepareDoc renvoie vers prepareDocServices
	// méthode protégé
	protected function onPrepareDoc(string $type,array $return):array 
	{
		$return = $this->prepareDocServices($type,$return);
		
		if($type === 'docClose')
		$return = $this->prepareDocJsInit($return);
		
		return $return;
	}
	
	
	// type
	// retourne le type de la route
	// si pas de type, utilise celui de boot
	// peut envoyer une exception
	public static function type():string
	{
		$return = static::$config['type'] ?? null;
		
		if(!is_string($return))
		{
			if(!is_string($return))
			{
				$boot = Boot::instReady();
				$return = $boot->type();
			}
			
			if(is_string($return))
			static::setType($return);
			
			else
			static::throw('noType');
		}
		
		return $return;
	}
	
	
	// setType
	// change le type de la route
	public static function setType(string $value,bool $dig=false):void 
	{
		static::$config['type'] = $value;
		
		if($dig === true)
		{
			$parent = get_parent_class(static::class);
			if(!empty($parent) && !in_array($parent,[Routing\Route::class,self::class],true))
			$parent::setType($value,$dig);
		}
		
		return;
	}
	
	
	// run
	// lance tout le processus de lancement et output de la route
	// retourne un tableau avec bool, continue et output
	// possible de echo le output si echo est true
	// une route qui retourne false ou qui lance une Routing\Exception ont la valeur continue à true (pour le loop)
	public function run(bool $echo=false):array
	{
		$return = ['bool'=>false,'continue'=>false,'output'=>null];
		$output = null;
		
		try 
		{
			$bool = false;
			$continue = false;
			$output = $this->start();
			
			if($output === false)
			$continue = true;
			
			else
			{
				$bool === true;
				
				if($echo === true)
				static::echoOutput($output);
			}
		} 
		
		catch (Routing\Exception $e) 
		{
			$e->onCatched();
			$continue = true;
		}
		
		catch (BootException $e) 
		{
			Base\Response::serverError();
			$e->onCatched();
		}
		
		$return['bool'] = $bool;
		$return['continue'] = $continue;
		$return['output'] = $output;
		
		return $return;
	}
	
	
	// getBaseReplace
	// retourne le tableau de remplacement de base
	// utilisé par docOpen et docClose
	public function getBaseReplace():array 
	{
		$return = [];
		$boot = static::boot();
		$lang = $boot->lang();
		$request = $this->request();
		$parent = static::parent();
		$description = $boot->description();
		$lang = $this->lang();
		
		$return['bootLabel'] = $boot->label();
		$return['bootDescription'] = $description;
		$return['lang'] = $lang->currentLang();
		$return['label'] = $this->title();
		$return['name'] = static::name();
		$return['type'] = static::type();
		$return['metaUri'] = $request->uri();
		$return['group'] = static::group(true);
		$return['parent'] = (!empty($parent))? $parent::name():null;
		$return['title'] = $return['label'];
		$return['metaKeywords'] = $lang->safe('meta/keywords');
		$return['metaDescription'] = $lang->safe('meta/description') ?? $description;
		$return['metaImage'] = $lang->safe('meta/image');
		$return['bodyClass'] = null;
		$return['bodyStyle'] = null;
		
		return $return;
	}
	
	
	// prepareTitle
	// prépare le titre après le onReplace
	protected function prepareTitle($return,array $array):array
	{
		$titleConfig = static::$config['metaTitle'] ?? [];
		
		if(!is_array($return))
		$return = [$return];
		
		$last = Base\Arr::valueLast($return);
		
		if(!empty($titleConfig['bootLabel']))
		{
			if(!empty($array['bootLabel']) && $last !== $array['bootLabel'])
			$return[] = $array['bootLabel'];
		}
		
		if(!empty($titleConfig['typeLabel']))
		{
			$type = static::type();
			$return[] = static::lang()->typeLabel($type);
		}
		
		return $return;
	}
	
	
	// prepareDocServices
	// méthode utilisé après prepareDoc, lie les tags de services pour docOpen et docClose
	// si un des éléments est false dans le tableau de config, à ce moment n'append pas le service (ça vaut dire que la route n'a pas de js/css/script)
	// méthode protégé 
	protected function prepareDocServices(string $type,array $return):array 
	{
		$boot = static::boot();
		$services = $boot->services();
		
		foreach ($services as $service) 
		{
			$key = $service->getKey();
			
			if($type === 'docOpen')
			{
				$return['head']['js'] = $return['head']['js'] ?? null;
				if($return['head']['js'] !== false)
				{
					$js = $service->docOpenJs();
					if(!empty($js))
					$return['head']['js'] = Base\Arr::append($return['head']['js'] ?? [],[$key=>$js]);
				}
				
				$return['head']['script'] = $return['head']['script'] ?? null;
				if($return['head']['script'] !== false)
				{
					$script = $service->docOpenScript();
					if(!empty($script))
					$return['head']['script'] = Base\Arr::append($return['head']['script'] ?? [],$script);
				}
			}
			
			elseif($type === 'docClose')
			{
				$return['script'] = $return['script'] ?? null;
				if($return['script'] !== false)
				{
					$script = $service->docCloseScript();
					if(!empty($script))
					$return['script'] = Base\Arr::append($return['script'] ?? [],$script);
				}
			}
		}

		return $return;
	}
	

	// prepareDocJsInit
	// ajoute la méthode jsInit si jsInit est true et que ce n'est pas une requête ajax
	protected function prepareDocJsInit(array $return):array 
	{
		if(static::$config['jsInit'] === true && $this->request()->isAjax() === false)
		{
			$callable = [static::class,'jsInit'];
			$return['script'] = Base\Arr::append($return['script'],[$callable]);
		}
		
		return $return;
	}
	
	
	// title
	// retourne le titre de la route triggé
	public function title($pattern=null,?string $lang=null,?array $option=null):?string
	{
		$return = null;
		$title = $this->makeTitle($lang);
		
		if(is_object($title))
		$title = Base\Obj::cast($title);
		
		if(is_string($title))
		{
			if(is_scalar($pattern))
			{
				$obj = static::boot()->lang();
				$option = Base\Arr::plus($option,['pattern'=>$pattern]);		
				$return = $obj->textAfter($title,$option);
			}
			
			elseif($pattern === null)
			$return = $title;
		}
		
		else
		static::throw('requiresString');
		
		return $return;
	}
	
	
	// rowExists
	// retourne vrai s'il y a une row lié à la route
	public function rowExists():bool 
	{
		return false;
	}
	
	
	// row
	// retourne la row lié à la route
	public function row():?Row 
	{
		return null;
	}
	
	
	// getOtherMeta
	// retourne la row meta lié à la route
	// par défaut vérifie si la row a l'interface meta
	public function getOtherMeta():?Main\Contract\Meta
	{
		$return = null;
		
		if($this->rowExists())
		{
			$row = $this->row();
			
			if($row instanceof Main\Contract\Meta)
			$return = $row;
		}
		
		return $return;
	}
	
	
	// allowed
	// retourne vrai si le role de la session courante permet d'accéder à la route
	// se base seulement sur match, pas verify
	public static function allowed(?Main\Role $role=null):bool
	{
		$return = false;
		$value = static::$config['match']['role'] ?? null;
		
		if(empty($role))
		{
			$boot = static::bootReady();
			if(!empty($boot))
			$return = Routing\RouteRequest::allowed($value,$boot->session()->role());
			
			elseif($value === null || $value === false)
			$return = true;
		}
		
		else
		$return = Routing\RouteRequest::allowed($value,$role);
		
		return $return;
	}
	
	
	// label
	// retourne le label de la route non triggé
	public static function label($pattern=null,?string $lang=null,?array $option=null):?string
	{
		$return = null;
		$obj = static::boot()->lang();
		$path = static::$config['label'] ?? null;
		$option = Base\Arr::plus($option,['pattern'=>$pattern]);
		
		if(!empty($path))
		$return = $obj->same($path,null,$lang,$option);
		else
		$return = $obj->routeLabel(static::name(),$lang,$option);
		
		return $return;
	}
	
	
	// description
	// retourne la description de la route non triggé
	public static function description($pattern=null,?array $replace=null,?string $lang=null,?array $option=null):?string
	{
		$return = null;
		$obj = static::boot()->lang();
		$path = static::$config['description'] ?? null;
		$option = Base\Arr::plus($option,['pattern'=>$pattern]);
		
		if(!empty($path))
		$return = $obj->same($path,$replace,$lang,$option);
		else
		$return = $obj->routeDescription(static::name(),$replace,$lang,$option);
		
		return $return;
	}
	
	
	// host
	// retourne le host pour la route
	// utilise le type de la route et la méthode host dans boot
	public static function host():?string
	{
		$return = null;
		$type = static::type();
		
		if(is_string($type))
		$return = static::boot()->host(true,$type);
		
		return $return;
	}
	
	
	// schemeHost
	// retourne le schemeHost pour la route
	// utilise le type de la route et la méthode schemeHost dans boot
	public static function schemeHost():?string 
	{
		$return = null;
		$type = static::type();
		
		if(is_string($type))
		$return = static::boot()->schemeHost(true,$type);
		
		return $return;
	}
	

	// context
	// retourne le tableau de contexte de la classe
	// le résultat est mis en cache
	// possible d'ajouter des éléments au tableau via arr/plus
	public static function context(?array $option=null):array 
	{
		$return = static::$context;
		
		if(!is_array($return))
		{
			$return = [];
			$boot = static::boot();
			$type = $boot->type();
			$env = $boot->env();
			$className = static::className();
			$context = $type.":".lcfirst($className);
			$return = static::$context = ['class'=>static::class,'type'=>$type,'env'=>$env,'context'=>$context];
		}
		
		if(!empty($option))
		$return = Base\Arr::plus($return,$option);
		
		return $return;
	}
	
	
	// routes
	// retourne l'objet routes de boot ou un nom de classe de route contenu dans l'objet
	public static function routes(bool $active=false,$get=null)
	{
		$boot = static::boot();
		$type = static::type();
		$return = ($active === true)? $boot->routesActive($type):$boot->routes($type);
		
		if(is_string($get))
		$return = $return->get($get);
		
		return $return;
	}
	
	
	// childs
	// retourne toutes les enfants de la route courante
	public static function childs(bool $active=false):Routing\Routes 
	{
		return static::routes($active)->childs(static::class);
	}
	
	
	// make
	// construit une instance de la route de façon statique
	public static function make($request=null,bool $overload=false):Routing\Route
	{
		$class = ($overload === true)? static::getOverloadClass():static::class;
		$return = new $class($request);
		
		return $return;
	}
	
	
	// makeOverload
	// construit une instance de la route de façon statique
	// overload est true
	public static function makeOverload($request=null):Routing\Route
	{
		return static::make($request,true);
	}
	

	// makeParent
	// retourne une instance la route parente
	// envoie une exception s'il n'y a pas de parent valide
	public static function makeParent($request=null,bool $overload=false):Routing\Route
	{
		$return = null;
		$parent = static::parent();
		
		if(empty($parent) || !is_subclass_of($parent,self::class,true))
		static::throw('invalidParent');
		
		$return = $parent::make($request,$overload);
		
		return $return;
	}
	
	
	// makeParentOverload
	// comme makeParent mais overload est à true
	public static function makeParentOverload($request=null):Routing\Route
	{
		return static::makeParent($request,true);
	}
	
	
	// tableSegment
	// reourne un objet table à partir du tableau keyValue utilisé dans segment
	// sinon, utilise la rowClass
	// peut retourner null
	// méthode protégé
	protected static function tableSegment(array &$keyValue):?Table
	{
		$return = null;
		$table = $keyValue['table'] ?? null;
		
		if(!empty($table))
		{
			$db = static::db();
			if($db->hasTable($table))
			$return = $db->table($table);
		}
		
		if(empty($return))
		{
			$rowClass = static::rowClass();
			if(!empty($rowClass))
			$return = $rowClass::tableFromFqcn();
		}
		
		return $return;
	}
	
	
	// rowClass
	// retourne la classe row lié à la route
	public static function rowClass():?string 
	{
		return static::$config['row'] ?? null;
	}
	
	
	// tableFromRowClass
	// retourne l'objet table à partir de la classe row lié à la route
	// envoie une exception si pas de rowClass
	public static function tableFromRowClass():Table 
	{
		$return = null;
		$row = static::rowClass();
		
		if(empty($row))
		static::throw('noRowClass');
		else
		$return = static::db()->table($row);
		
		return $return;
	}
	
	
	// jsInit
	// méthode statique qui permet d'init le javascript sur la page
	public static function jsInit():string 
	{
		return '$(document).ready(function() { $(this).navigation(); });';
	}
	
	
	// getOverloadKeyPrepend
	// retourne le prepend de la clé à utiliser pour le tableau overload
	public static function getOverloadKeyPrepend():?string 
	{
		return (static::class !== self::class && !Base\Fqcn::sameName(static::class,self::class))? 'Route':null;
	}
}

// config
Route::__config();
?>
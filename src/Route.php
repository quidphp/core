<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Routing;
use Quid\Main;
use Quid\Base;

// route
// extended abstract class for a route that acts as both a View and a Controller
abstract class Route extends Routing\Route
{
	// trait
	use _fullAccess;


	// config
	public static $config = [ // config pour la route
		'metaTitle'=>['bootLabel'=>true,'typeLabel'=>false], // éléments à ajouter à la fin du titre
		'row'=>null // permet de spécifier la classe row en lien avec la route
	];


	// onPrepareDoc
	// prepareDoc renvoie vers prepareDocServices
	// méthode protégé
	protected function onPrepareDoc(string $type,array $return):array
	{
		$return = parent::onPrepareDoc($type,$return);
		$return = $this->prepareDocServices($type,$return);

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


	// context
	// retourne le tableau de contexte de la classe
	// possible d'ajouter des éléments au tableau via arr/plus
	public function context(?array $option=null):array
	{
		return $this->cache(__METHOD__,function() use($option) {
			$return = [];
			$boot = static::boot();
			$type = $boot->type();
			$env = $boot->env();
			$className = static::className();
			$context = $type.':'.lcfirst($className);
			$return = ['class'=>static::class,'type'=>$type,'env'=>$env,'context'=>$context];

			if(!empty($option))
			$return = Base\Arr::plus($return,$option);

			return $return;
		});
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


	// routeBaseClasses
	// retourne les classes bases de routes (donc abstraite)
	public static function routeBaseClasses():array
	{
		return array(self::class,Routing\Route::class);
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
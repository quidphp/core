<?php
declare(strict_types=1);
namespace Quid\Core\Route;
use Quid\Core;
use Quid\Main;

// sitemap
abstract class Sitemap extends Core\RouteAlias
{
	// config
	public static $config = [
		'path'=>'sitemap.xml',
		'priority'=>500,
		'sitemap'=>false,
		'group'=>'seo',
		'response'=>[
			'contentType'=>'xml']
	];
	
	
	// trigger
	// lance la route sitemap et génère toutes les uris accessible à l'utilisateur de la session courante
	public function trigger()
	{
		$r = '';
		$xml = Main\Xml::newOverload('sitemap');
		$lang = $this->lang();
		$routes = $this->routes(true)->sortDefault();
		$uris = $routes->sitemap($lang->allLang());
		
		if(!empty($uris))
		$xml->sitemap(...$uris);
		
		$r = $xml->output();
		
		return $r;
	}
}

// config
Sitemap::__config();
?>
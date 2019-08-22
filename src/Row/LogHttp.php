<?php
declare(strict_types=1);
namespace Quid\Core\Row;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// logHttp
class LogHttp extends Core\RowAlias implements Main\Contract\Log
{
	// trait
	use _log;
	
	
	// config
	public static $config = [
		'panel'=>false,
		'search'=>false,
		'parent'=>'system',
		'priority'=>1002,
		'cols'=>[
			'context'=>['class'=>Core\Col\Context::class],
			'request'=>['class'=>Core\Col\Request::class],
			'type'=>['general'=>true,'relation'=>'logHttpType'],
			'json'=>['class'=>Core\Col\JsonExport::class]],
		'deleteTrim'=>500, // custom
		'block'=>[ // liste des patterns de chemins à ne pas logger, insensible à la case
			'apple-touch-icon',
			'browserconfig.xml',
			'autodiscover.xml'],
		'type'=>[ // type de logHttp
			1=>'unsafe',
			2=>'redirection',
			3=>'request',
			4=>'externalPost',
			200=>200,
			301=>301,
			302=>302,
			400=>400,
			404=>404,
			500=>500]
	];
	
	
	// shouldLog
	// retourne vrai s'il faut logger la route à partir du chemin
	// voir static config block
	public static function shouldLog(Main\Request $request):bool 
	{
		return (Base\Arr::hasValueStart($request->pathStripStart(),static::$config['block'] ?? [],false))? false:true;
	}
	
	
	// getTypeCode
	// retourne le code à partir du type
	public static function getTypeCode($type):?int 
	{
		return (in_array($type,static::$config['type'],true))? array_search($type,static::$config['type'],true):null;
	}
	
	
	// newData
	// crée le tableau d'insertion
	public static function newData($type,$json=null):?array
	{
		$return = null;
		$type = static::getTypeCode($type);
		
		if(is_int($type))
		$return = ['type'=>$type,'json'=>$json];
		
		return $return;
	}
	
	
	// onCloseDown
	// ajoute un log au closeDown si le code de réponse n'est pas positif (pas 200, 301 ou 302)
	// et si la requête doit être loggé dans shouldLOg
	public static function onCloseDown():void
	{
		Base\Response::onCloseDown(function() {
			
			$code = Base\Response::code();
			$data = [];
			$bool = false;
			
			$boot = static::bootReady();
			if(!empty($boot))
			{
				$request = $boot->request();
				
				if(static::shouldLog($request))
				{
					$shouldLog = (Base\Response::isCodePositive())? false:true;
					$data = $request->getLogData();
					if(!empty($data))
					$shouldLog = true;
					
					if($shouldLog === true)
					{
						$bool = true;
						$route = $boot->route();
						
						if(!empty($route))
						$data['route'] = $route;
					}
				}
			}
			
			if($bool === true)
			{
				$data = (empty($data))? null:$data;
				static::new($code,$data);
				static::logTrim();
			}
		});
		
		return;
	}
}

// config
LogHttp::__config();
?>
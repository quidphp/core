<?php
declare(strict_types=1);
namespace Quid\Core\Row;
use Quid\Core;
use Quid\Main;

// log
class Log extends Core\RowAlias implements Main\Contract\Log
{
	// trait
	use _log;
	
	
	// config
	public static $config = [
		'panel'=>false,
		'search'=>false,
		'priority'=>1000,
		'parent'=>'system',
		'cols'=>[
			'context'=>['class'=>Core\Col\Context::class],
			'request'=>['class'=>Core\Col\Request::class],
			'type'=>['general'=>true,'relation'=>'logType'],
			'json'=>['class'=>Core\Col\JsonExport::class]],
		'deleteTrim'=>500, // custom
		'type'=>[ // type de log
			1=>'login',
			2=>'logout',
			3=>'resetPassword',
			4=>'activatePassword',
			5=>'changePassword',
			6=>'register']
	];
	
	
	// getTypeCode
	// retourne le code à partir du type
	public static function getTypeCode(string $type):int 
	{
		return (in_array($type,static::$config['type'],true))? array_search($type,static::$config['type'],true):0;
	}
	
	
	// newData
	// crée le tableau d'insertion
	public static function newData(string $type,array $json):array
	{
		return ['type'=>static::getTypeCode($type),'json'=>$json];
	}
}

// config
Log::__config();
?>
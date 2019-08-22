<?php
declare(strict_types=1);
namespace Quid\Core\Row;
use Quid\Core;
use Quid\Main;

// logEmail
class LogEmail extends Core\RowAlias implements Main\Contract\Log
{
	// trait
	use _log;
	
	
	// config
	public static $config = [
		'panel'=>false,
		'search'=>false,
		'parent'=>'system',
		'priority'=>1004,
		'cols'=>[
			'context'=>['class'=>Core\Col\Context::class],
			'request'=>['class'=>Core\Col\Request::class],
			'status'=>['class'=>Core\Col\Boolean::class],
			'json'=>['class'=>Core\Col\JsonExport::class]],
		'deleteTrim'=>500 // custom
	];
	
	
	// newData
	// crée le tableau d'insertion
	public static function newData(bool $status,array $json):array
	{
		return ['status'=>(int) $status,'json'=>$json];
	}
}

// config
LogEmail::__config();
?>
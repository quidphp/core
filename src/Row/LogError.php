<?php
declare(strict_types=1);
namespace Quid\Core\Row;
use Quid\Core;
use Quid\Main;

// logError
class LogError extends Core\RowAlias implements Main\Contract\Log
{
	// trait
	use _log;
	
	
	// config
	public static $config = [
		'panel'=>false,
		'search'=>false,
		'parent'=>'system',
		'priority'=>1001,
		'cols'=>[
			'context'=>['class'=>Core\Col\Context::class],
			'request'=>['class'=>Core\Col\Request::class],
			'type'=>['general'=>true,'relation'=>'error/label'],
			'error'=>['required'=>true,'class'=>Core\Col\Error::class]],
		'deleteTrim'=>500 // custom
	];
	
	
	// newData
	// crée le tableau d'insertion
	public static function newData(Core\Error $error):array
	{
		$return = [];
		$return['type'] = $error->getCode();
		$return['error'] = $error->toArray();
		
		return $return;
	}
}

// config
LogError::__config();
?>
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
	public static $config = array(
		'panel'=>false,
		'search'=>false,
		'parent'=>'system',
		'priority'=>1004,
		'cols'=>array(
			'context'=>array('class'=>Core\Col\Context::class),
			'request'=>array('class'=>Core\Col\Request::class),
			'status'=>array('class'=>Core\Col\Boolean::class),
			'json'=>array('class'=>Core\Col\JsonExport::class)),
		'deleteTrim'=>500 // custom
	);
	
	
	// newData
	// crée le tableau d'insertion
	public static function newData(bool $status,array $json):array
	{
		return array('status'=>(int) $status,'json'=>$json);
	}
}

// config
LogEmail::__config();
?>
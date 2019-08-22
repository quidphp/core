<?php
declare(strict_types=1);
namespace Quid\Core\Row;
use Quid\Core;
use Quid\Main;

// logCron
class LogCron extends Core\RowAlias implements Main\Contract\Log
{
	// trait
	use _log;
	
	
	// config
	public static $config = array(
		'panel'=>false,
		'search'=>false,
		'parent'=>'system',
		'priority'=>1005,
		'cols'=>array(
			'context'=>array('class'=>Core\Col\Context::class),
			'type'=>array('general'=>true,'relation'=>'logCronType'),
			'json'=>array('class'=>Core\Col\JsonExport::class)),
		'deleteTrim'=>500 // custom
	);
	
	
	// newData
	// crée le tableau d'insertion
	public static function newData():array
	{
		return array();
	}
}

// config
LogCron::__config();
?>
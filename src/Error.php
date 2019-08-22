<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Main;
use Quid\Base;

// error
class Error extends Main\Error
{	
	// config
	public static $config = array(
		'option'=>array( // tableau d'options
			'log'=>array(Row\LogError::class,File\Error::class)), // classe pour log, , s'il y a en plusieurs utilise seulement le premier qui fonctionne
		'type'=>array( // description des types additionneles à boot
			33=>array('key'=>'dbException','name'=>'Database Exception'),
			34=>array('key'=>'routeException','name'=>'Route Exception'),
			35=>array('key'=>'bootException','name'=>'Boot Exception'))
	); 
	
	
	// init
	// initialise la prise en charge des erreurs, exception et assertion
	public static function init():void
	{
		parent::init();
		Base\Error::setHandler(array(static::class,'handler'));
		Base\Exception::setHandler(array(static::class,'exception'));
		Base\Assert::setHandler(array(static::class,'assert'));
		
		return;
	}
}

// config
Error::__config();
?>
<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Orm;

// active
class Active extends YesAlias
{
	// config
	public static $config = [];
	
	
	// onDuplicate
	// callback sur duplication, retourne null
	public function onDuplicate($return,array $row,Orm\Cell $cell,array $option) 
	{
		return null;
	}
	
	
	// classHtml
	// retourne la classe à utiliser en html pour active
	public function classHtml():string 
	{
		$class = parent::class;
		return $class::className(true);
	}
}

// config
Active::__config();
?>
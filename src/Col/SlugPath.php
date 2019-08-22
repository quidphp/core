<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Base;

// slugPath
class SlugPath extends SlugAlias
{
	// config
	public static $config = array(
		'validate'=>array(1=>'slugPath')
	);
	
	
	// slugMake
	// gère l'appel à la classe base/slugPath
	public static function slugMake($value,?array $option=null):string 
	{
		return Base\SlugPath::str($value,array('slug'=>$option));
	}
}

// config
SlugPath::__config();
?>
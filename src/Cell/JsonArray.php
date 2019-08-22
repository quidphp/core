<?php
declare(strict_types=1);
namespace Quid\Core\Cell;
use Quid\Core;

// jsonArray
class JsonArray extends Core\CellAlias
{
	// config
	public static $config = array();
	
	
	// index
	// retourne un index de jsonArray
	public function index(int $value) 
	{
		$return = null;
		$get = $this->get();
		
		if(is_array($get) && array_key_exists($value,$get))
		$return = $get[$value];
		
		return $return;
	}
}

// config
JsonArray::__config();
?>
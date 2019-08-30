<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cell;
use Quid\Core;

// jsonArray
// class to manage a cell containing a json array
class JsonArray extends Core\CellAlias
{
	// config
	public static $config = [];


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
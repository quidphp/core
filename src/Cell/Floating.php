<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cell;
use Quid\Core;
use Quid\Base;

// floating
// class to work with a cell containing a floating value
class Floating extends Core\CellAlias
{
	// config
	public static $config = [];


	// pair
	// retourne la date formatté
	// sinon renvoie à parent
	public function pair($value=null,...$args)
	{
		$return = $this;

		if($value === '$')
		$return = $this->moneyFormat(...$args);

		elseif($value !== null)
		$return = parent::pair($value,...$args);

		return $return;
	}


	// moneyFormat
	// format le nombre flottant en argent
	public function moneyFormat(?string $lang=null,?array $option=null):?string
	{
		return Base\Number::moneyFormat($this->value(),$lang,$option);
	}
}

// config
Floating::__config();
?>
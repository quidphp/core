<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Segment;

// _page
trait _page
{
	// structureSegmentPage
	// gère le segment d'uri pour une page, doit être un int plus grand que 0
	public static function structureSegmentPage(string $type,$value,array &$keyValue)
	{
		$return = false;

		if($type === 'make')
		$return = (is_int($value) && $value > 0)? $value:1;

		elseif($type === 'validate')
		{
			if(is_scalar($value))
			$return = (is_int($value) && $value > 0)? $value:false;

			else
			$return = 1;
		}

		elseif($type === 'validateDefault')
		$return = 1;

		return $return;
	}
}
?>
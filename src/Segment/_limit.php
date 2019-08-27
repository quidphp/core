<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Segment;

// _limit
trait _limit
{
	// structureSegmentLimit
	// gère le segment d'uri pour une limite, doit être un int plus grand que 0
	public static function structureSegmentLimit(string $type,$value,array &$keyValue)
	{
		$return = false;

		if($type === 'make')
		$return = (is_int($value) && $value > 0)? $value:false;

		elseif($type === 'validate')
		$return = (is_int($value) && $value > 0)? $value:false;

		elseif($type === 'validateDefault')
		{
			$table = static::tableSegment($keyValue);

			if(!empty($table))
			$return = $table->limit();
		}

		return $return;
	}
}
?>
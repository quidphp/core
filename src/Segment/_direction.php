<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Segment;
use Quid\Base;

// _direction
trait _direction
{
	// structureSegmentDirection
	// gère le segment d'uri pour une direction de tri
	public static function structureSegmentDirection(string $type,$value,array &$keyValue)
	{
		$return = false;

		if($type === 'make')
		$return = (is_string($value) && !empty($value))? strtolower($value):false;

		elseif($type === 'validate')
		$return = (Base\Sql::isOrderDirection($value))? strtolower($value):false;

		elseif($type === 'validateDefault')
		{
			$table = static::tableSegment($keyValue);

			if(!empty($table))
			$return = $table->order('direction');
		}

		return $return;
	}
}
?>
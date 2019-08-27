<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Segment;
use Quid\Core;

// _table
trait _table
{
	// structureSegmentTable
	// gère le segment d'uri pour une table
	public static function structureSegmentTable(string $type,$value,array &$keyValue)
	{
		$return = false;

		if($type === 'make')
		{
			if($value instanceof Core\Row || $value instanceof Core\Col || $value instanceof Core\Cell)
			$value = $value->table();

			if(is_string($value) || $value instanceof Core\Table)
			$return = $value;
		}

		elseif($type === 'validate')
		{
			$db = static::db();

			if($db->hasTable($value))
			$return = $db->table($value);
		}

		return $return;
	}
}
?>
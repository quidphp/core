<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Segment;

// _orderColRelation
trait _orderColRelation
{
	// structureSegmentOrderColRelation
	// gère le segment order avec colonne pour une route relation
	public static function structureSegmentOrderColRelation(string $type,$value,array &$keyValue)
	{
		$return = false;

		if($type === 'make')
		$return = (is_scalar($value))? $value:false;

		elseif($type === 'validate')
		{
			$db = static::db();
			if($db->hasTable($keyValue['table']))
			{
				$table = $db->table($keyValue['table']);

				if($table->hasCol($keyValue['col']))
				{
					$col = $table->col($keyValue['col']);

					if(static::isValidOrder($value,$col->relation()))
					$return = $value;
				}
			}
		}

		elseif($type === 'validateDefault')
		$return = static::$config['order'] ?? false;

		return $return;
	}
}
?>
<?php
declare(strict_types=1);
namespace Quid\Core\Segment;

// _orderTableRelation
trait _orderTableRelation
{
	// structureSegmentOrderTableRelation
	// gère le segment order pour une route relation
	public static function structureSegmentOrderTableRelation(string $type,$value,array &$keyValue) 
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
				
				if(static::isValidOrder($value,$table->relation()))
				$return = $value;
			}
		}
		
		elseif($type === 'validateDefault')
		$return = static::$config['order'] ?? false;
		
		return $return;
	}
}
?>
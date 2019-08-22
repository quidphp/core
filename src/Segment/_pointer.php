<?php
declare(strict_types=1);
namespace Quid\Core\Segment;
use Quid\Core;

// _pointer
trait _pointer
{
	// structureSegmentPointer
	// gère le segment d'uri pour un pointeur, c'est à dire table-id en un seul segment
	public static function structureSegmentPointer(string $type,$value,array &$keyValue) 
	{
		$return = false;
		$valid = static::getPointerValidTables();
		
		if($type === 'make')
		{
			if($value instanceof Core\Row)
			{
				$tableName = $value->tableName();
				
				if(empty($valid) || in_array($tableName,$valid,true))
				$value = $value->pointer('-');
				
				else
				$return = false;
			}
			
			if(is_string($value) && !empty($value))
			$return = $value;
		}
		
		elseif($type === 'validate')
		{
			if(is_string($value) && !empty($value))
			{
				$db = static::db();
				$return = $db->fromPointer($value,'-',$valid) ?? false;
			}
		}
		
		elseif($type === 'validateDefault')
		$return = static::structureSegmentPointerValidateDefault();
		
		return $return;
	}
	
	
	// getPointerValidTables
	// retourne les tables valables pour le pointeur, si vide tout est valable
	public static function getPointerValidTables():?array 
	{
		return null;
	}
	
	
	// structureSegmentPointerValidateDefault
	// retourne la valeur par défaut pour le segment
	public static function structureSegmentPointerValidateDefault() 
	{
		return false;
	}
}
?>
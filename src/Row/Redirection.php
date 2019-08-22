<?php
declare(strict_types=1);
namespace Quid\Core\Row;
use Quid\Core;

// redirection
class Redirection extends Core\RowAlias
{
	// config
	public static $config = array(
		'panel'=>false,
		'key'=>'key',
		'order'=>array('id'=>'desc'),
		'parent'=>'system',
		'priority'=>951,
		'searchMinLength'=>1,
		'cols'=>array(
			'active'=>true,
			'type'=>array('class'=>Core\Col\ContextType::class),
			'key'=>array('general'=>true),
			'value'=>array('general'=>true,'required'=>true))
	);
	
	
	// grabContent
	// retourne un tableau de tous les contenus de redirection pertinente
	// il faut fournir un un type
	public static function grabContent(string $type):array 
	{
		$return = array();
		$table = static::tableFromFqcn();
		$typeCol = $table->col('type');
		$keyCol = $table->colKey();
		$valueCol = $table->col("value");
		$where = array(true,array($typeCol,'findInSet',$type));
		$return = $table->keyValue($keyCol,$valueCol,false,$where);
		
		return $return;
	}
}

// config
Redirection::__config();
?>
<?php
declare(strict_types=1);
namespace Quid\Core\Row;
use Quid\Core;
use Quid\Base;

// lang
class Lang extends Core\RowAlias
{
	// config
	public static $config = array(
		'panel'=>false,
		'key'=>'key',
		'order'=>array('id'=>'desc'),
		'parent'=>'system',
		'priority'=>950,
		'searchMinLength'=>1,
		'cols'=>array(
			'active'=>true,
			'type'=>array('class'=>Core\Col\ContextType::class),
			'key'=>true,
			'content_fr'=>array('general'=>true,'class'=>Core\Col\Textarea::class,'required'=>true,'exists'=>false),
			'content_en'=>array('general'=>true,'class'=>Core\Col\Textarea::class,'required'=>true,'exists'=>false))
	);
	
	
	// grabContent
	// retourne un tableau de tous les contenus de langue pertinente
	// il faut fournir un code de langue et un type
	public static function grabContent(string $value,string $type):array 
	{
		$return = array();
		$table = static::tableFromFqcn();
		$typeCol = $table->col('type');
		$keyCol = $table->colKey();
		$contentCol = $table->col("content_$value");
		$where = array(true,array($typeCol,'findInSet',$type));
		$return = $table->keyValue($keyCol,$contentCol,false,$where);
		
		if(!empty($return))
		$return = Base\Lang::content($return);
		
		return $return;
	}
}

// config
Lang::__config();
?>
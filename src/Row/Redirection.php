<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Row;
use Quid\Core;

// redirection
class Redirection extends Core\RowAlias
{
	// config
	public static $config = [
		'panel'=>false,
		'key'=>'key',
		'order'=>['id'=>'desc'],
		'parent'=>'system',
		'priority'=>951,
		'searchMinLength'=>1,
		'cols'=>[
			'active'=>true,
			'type'=>['class'=>Core\Col\ContextType::class],
			'key'=>['general'=>true],
			'value'=>['general'=>true,'required'=>true]]
	];


	// grabContent
	// retourne un tableau de tous les contenus de redirection pertinente
	// il faut fournir un un type
	public static function grabContent(string $type):array
	{
		$return = [];
		$table = static::tableFromFqcn();
		$typeCol = $table->col('type');
		$keyCol = $table->colKey();
		$valueCol = $table->col('value');
		$where = [true,[$typeCol,'findInSet',$type]];
		$return = $table->keyValue($keyCol,$valueCol,false,$where);

		return $return;
	}
}

// config
Redirection::__config();
?>
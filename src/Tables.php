<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Orm;

// tables
class Tables extends Orm\Tables
{
	// trait
	use _accessAlias;
	
	
	// config
	public static $config = [];
	
	
	// keyClassExtends
	// retourne un tableau utilisé par onPrepareKey
	public static function keyClassExtends():array
	{
		return [Row::class,Table::class,Rows::class,Cells::class,Cols::class];
	}
}
?>
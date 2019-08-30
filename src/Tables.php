<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Orm;

// tables
// extended class for a collection of many tables within a same database
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
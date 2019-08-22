<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Orm;

// _dbAccess
trait _dbAccess
{
	// db
	// retourne l'objet db de boot
	public static function db():Orm\Db 
	{
		return static::boot()->db();
	}
}
?>
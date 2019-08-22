<?php
declare(strict_types=1);
namespace Quid\Core\Row;
use Quid\Main;

// _queue
trait _queue
{
	// trait
	use Main\_queue, _new;
	
	
	// getQueued
	// retourne un objet rows avec toutes les rows queued
	abstract public static function getQueued(?int $limit=null):?Main\Map;
	
	
	// queue
	// créer une nouvelle entrée dans la queue
	public static function queue(...$values):?Main\Contract\Queue
	{
		return static::new(...$values);
	}
}
?>
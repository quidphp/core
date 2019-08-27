<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Row;
use Quid\Main;

// _log
trait _log
{
	// trait
	use Main\_log;
	use _new;
	
	
	// log
	// crée une nouvelle entrée du log maintenant
	public static function log(...$values):?Main\Contract\Log
	{
		return static::new(...$values);
	}


	// logTrim
	// trim la table de log pour la valeur paramétré dans static config
	// le maximum de vérification sont faites pour ne pas qu'il y ait d'erreurs de générer dans la méthode
	public static function logTrim():?int
	{
		$return = null;

		if(array_key_exists('deleteTrim',static::$config) && is_int(static::$config['deleteTrim']))
		{
			$boot = static::bootReady();

			if(!empty($boot) && $boot->hasDb())
			{
				$db = $boot->db();

				if($db->hasTable(static::class))
				{
					$table = $db->table(static::class);

					if($table->hasPermission('delete'))
					{
						$currentLog = $db->getOption('log');
						$db->off();
						$return = $table->deleteTrim(static::$config['deleteTrim'],true);
						$db->on();
					}
				}
			}
		}

		return $return;
	}
}
?>
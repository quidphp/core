<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Row;
use Quid\Core;
use Quid\Main;

// _new
// trait that grants access some methods which allows creating rows statically
trait _new
{
	// newData
	// génère les données à mettre dans la ligne
	abstract public static function newData():array;


	// newTable
	// retourne l'objet table de la classe
	// n'envoie pas d'erreur si la table n'existe pas ou si boot n'est pas prêt
	public static function newTable():?Core\Table
	{
		$return = null;
		$boot = static::bootReady();

		if(!empty($boot) && $boot->hasDb() && $boot->hasSession())
		{
			$db = $boot->db();

			if($db->hasTable(static::class))
			$return = $db->table(static::class);
		}

		return $return;
	}


	// new
	// créer une nouvelle ligne dans la table et retourne le insertId
	// permet de créer des lignes facilement de façon statique
	// les données sont passés dans la méthode abstraite newData avant d'être insérés
	// le maximum de vérification sont faites pour ne pas qu'il y ait d'erreurs de générer dans la méthode
	// reservePrimary est false (donc sauve 2 requêtes)
	// si newData retourne null, n'insère pas la ligne -> skip
	public static function new(...$values):?Core\Row
	{
		$return = null;
		$table = static::newTable();

		if(!empty($table) && $table->hasPermission('insert'))
		{
			$db = $table->db();
			$data = static::newData(...$values);

			if($data !== null)
			{
				$db->off();
				$return = $table->insert($data,['strict'=>true,'reservePrimary'=>false]);
				$db->on();
			}
		}

		return $return;
	}


	// newOverload
	// fait un overload sur la classe et ensuite passe à new
	public static function newOverload(...$values):Main\Root
	{
		return static::getOverloadClass()::new(...$values);
	}
}
?>
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cell;
use Quid\Core;
use Quid\Orm;

// primary
// class for dealing with a the cell of a column which has an auto increment primary key
class Primary extends Core\CellAlias
{
	// config
	public static $config = [];


	// set
	// set pour cell primaire n'est pas permis
	// aucune erreur envoyé si le id est le même qu'initial
	public function set($value,?array $option=null):Orm\Cell
	{
		if(!(is_int($value) && !empty($this->value['initial']) && $this->value['initial'] === $value))
		static::throw();

		return $this;
	}


	// setInitial
	// setInitial pour cell primaire est seulement permis si:
	// il n'y pas de valeur initial ou si la valeur donné est la valeur initial
	// onInit n'est pas appelé
	public function setInitial($value):Orm\Cell
	{
		if(is_int($value) && (empty($this->value['initial']) || $this->value['initial'] === $value))
		{
			if(empty($this->value['initial']))
			$this->value['initial'] = $value;
		}

		else
		static::throw();

		return $this;
	}


	// reset
	// reset pour cell primaire n'est pas permis
	public function reset():Orm\Cell
	{
		static::throw();

		return $this;
	}


	// unset
	// unset pour cell primaire n'est pas permis
	public function unset():Orm\Cell
	{
		static::throw();

		return $this;
	}
}

// config
Primary::__config();
?>
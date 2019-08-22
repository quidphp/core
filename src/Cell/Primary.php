<?php
declare(strict_types=1);
namespace Quid\Core\Cell;
use Quid\Core;
use Quid\Orm;

// primary
final class Primary extends Core\CellAlias
{
	// config
	public static $config = array();
	
	
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
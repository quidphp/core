<?php
declare(strict_types=1);
namespace Quid\Core\Cell;
use Quid\Core;

// integer
class Integer extends Core\CellAlias
{
	// config
	public static $config = [];
	
	
	// increment
	// increment la valeur de la cell
	public function increment():self 
	{
		$value = $this->value();
		$value = (is_int($value))? ($value+1):1;
		$this->set($value);
		
		return $this;
	}
	
	
	// decrement
	// decrement la valeur de la cell
	public function decrement():self 
	{
		$value = $this->value();
		$value = (is_int($value) && $value > 1)? ($value-1):0;
		$this->set($value);
		
		return $this;
	}
}

// config
Integer::__config();
?>
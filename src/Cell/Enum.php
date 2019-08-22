<?php
declare(strict_types=1);
namespace Quid\Core\Cell;
use Quid\Core;

// enum
class Enum extends RelationAlias
{
	// config
	public static $config = array();
	
	
	// cast
	// pour cast de cellule relation retourne get plutôt que value
	public function _cast() 
	{
		return $this->get();
	}
	
	
	// relation
	// gère le retour d'une valeur de relation pour enum
	// cache est true
	public function relation(?array $option=null)
	{
		return $this->colRelation()->get($this,false,true,$option);
	}
	
	
	// relationRow
	// retourne la valeur de la relation sous forme de row, peut retourner null
	// envoie une exception si le type de relation n'est pas table
	public function relationRow(?array $option=null):?Core\Row
	{
		return $this->colRelation()->getRow($this,$option);
	}
}

// config
Enum::__config();
?>
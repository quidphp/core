<?php
declare(strict_types=1);
namespace Quid\Core\Cell;
use Quid\Core;

// jsonArrayRelation
class JsonArrayRelation extends Core\CellAlias
{
	// config
	public static $config = [];
	
	
	// relationRow
	// retourne la row de relation pour la cellule
	// peut envoyer une exception
	public function relationRow():?Core\Row 
	{
		$return = null;
		$fromCell = $this->fromCell();
		$row = $this->row();
		
		if(!empty($fromCell))
		{
			$cell = $row->cell($fromCell);
			$return = $cell->relationRow();
		}
		
		else
		static::throw();
		
		return $return;
	}
	
	
	// relationIndex
	// retourne la valeur index du input jsonArray
	// relationCols doit contenir deux noms de colonnes
	// peut envoyer une exception
	public function relationIndex(int $value) 
	{
		$return = null;
		$toCell = $this->toCell();
		
		if(!empty($toCell))
		{
			$relation = $this->relationRow();
			
			if(!empty($relation))
			{
				$cell = $relation->cell($toCell);
				$return = $cell->index($value);
			}
		}
		
		else
		static::throw();
		
		return $return;
	}
	
	
	// fromCell
	// retourne la cellule from de la ligne courante
	public function fromCell():?string 
	{
		$return = null;
		$relationCols = $this->attr('relationCols');
		
		if(is_array($relationCols) && count($relationCols) === 2)
		$return = $relationCols[0];
		
		return $return;
	}
	
	
	// toCell
	// retourne la cellule to de la ligne de relation
	public function toCell():?string 
	{
		$return = null;
		$relationCols = $this->attr('relationCols');
		
		if(is_array($relationCols) && count($relationCols) === 2)
		$return = $relationCols[1];
		
		return $return;
	}
}

// config
JsonArrayRelation::__config();
?>
<?php
declare(strict_types=1);
namespace Quid\Core\Route;
use Quid\Core;

// _specificPrimary
trait _specificPrimary
{
	// onBefore
	// avant le lancement de la route
	protected function onBefore() 
	{
		return $this->row()->isVisible();
	}
	
	
	// rowExists
	// retourne vrai si la row existe
	public function rowExists():bool 
	{
		return ($this->segment('primary') instanceof Core\Row)? true:false;
	}
	
	
	// row
	// retourne la row pour specific
	public function row():Core\Row 
	{
		return $this->segment('primary');
	}
}
?>
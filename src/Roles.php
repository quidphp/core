<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Main;

// roles
class Roles extends Main\Roles
{
	// trait
	use _fullAccess;
	
	
	// init
	// init l'objet roles
	// simplement un sort default
	public function init(string $type):self 
	{
		$this->sortDefault();
		
		return $this;
	}
}

// config
Roles::__config();
?>
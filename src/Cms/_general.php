<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;

// _general
trait _general
{
	// table
	// retourne la table en lien avec la route
	public function table():Core\Table 
	{
		return $this->cache(__METHOD__,function() {
			$return = $this->segment('table');
			
			if(is_string($return))
			$return = static::boot()->db()->table($return);
			
			return $return;
		});
	}
	
	
	// general
	// retourne la route general a utilisé pour rediriger
	public function general(bool $nav=true):General
	{
		return $this->cache(__METHOD__,function() use($nav) {
			return static::session()->routeTableGeneral($this->table(),$nav);
		});
	}
}
?>
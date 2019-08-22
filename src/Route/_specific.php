<?php
declare(strict_types=1);
namespace Quid\Core\Route;
use Quid\Core;

// _specific
trait _specific
{
	// trait
	use _specificNav;
	
	
	// config
	public static $configGeneral = array(
		'group'=>'specific'
	);
	
	
	// selectedUri
	public function selectedUri():array
	{
		return static::makeParent()->selectedUri();
	}
	
	
	// general
	public function general():Core\Route 
	{
		return $this->cache(__METHOD__,function() {
			$return = null;
			$parent = static::parent();
			
			if(!empty($parent))
			$return = $parent::makeGeneral();
			
			return $return;
		});
	}
	
	
	// getBreadcrumbs
	public function getBreadcrumbs():array 
	{
		return array(static::makeParent(),$this);
	}
}
?>
<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;
use Quid\Base;

// _specificSubmit
trait _specificSubmit
{
	// routeSuccess
	// retourne la route en cas de succès ou échec de l'ajout
	public function routeSuccess():string
	{
		return $this->specificWithFragment();
	}
	
	
	// currentPanel
	// retourne le panel courant à partir de la requête
	public function currentPanel():?string 
	{
		return $this->request()->get(static::panelInputName());
	}
	
	
	// specific
	// retourne la route parent, peut retourner specific ou specificAdd
	public function specific():Core\Route
	{
		$return = null;
		$parent = static::parent();
		
		if(empty($parent))
		static::throw();
		
		$return = $parent::makeOverload($this->segment());

		return $return;
	}
	
	
	// specificWithFragment
	// retourne la route specific mais ajoute le fragment du panel
	public function specificWithFragment():string
	{
		$return = $this->specific()->uri();
		
		$fragment = $this->currentPanel();
		if(!empty($fragment))
		$return = Base\Uri::changeFragment($fragment,$return);

		return $return;
	}
}
?>
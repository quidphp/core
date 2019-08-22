<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;

// error
class Error extends Core\Route\Error
{
	// trait
	use _templateAlias;
	
	
	// config
	public static $config = [];
	
	
	// main
	// génère la page erreur dans la balise main
	protected function main():string 
	{
		return $this->html();
	}
	
	
	// somebody
	// génère la page erreur si l'utilisateur est somebody (connecté)
	protected function somebody():string 
	{
		return $this->detail(Home::makeOverload());
	}
}

// config
Error::__config();
?>
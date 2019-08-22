<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Base\Html;
use Quid\Core;

// register
class Register extends Core\Route\Register
{
	// trait
	use _nobody;
	
	
	// config
	public static $config = array(
		'parent'=>Login::class,
		'row'=>Core\Row\User::class
	);
	
	
	// submitClass
	// classe de la route pour soumettre le formulaire
	public static function submitClass():string
	{
		return RegisterSubmit::getOverloadClass();
	}
	
	
	// submitAttr
	// attribut pour le bouton submit du formulaire
	public function submitAttr() 
	{
		return array('icon','padLeft','add');
	}
	
	
	// makeButtons
	// retourne un tableau avec les boutons sous le formulaire de connexion
	protected function makeButtons():array 
	{
		$return = array();
		$return['login'] = $this->makeLogin();
		$return['resetPassword'] = $this->makeResetPassword();
		$return['about'] = $this->makeAbout();
		
		return $return;
	}
}

// config
Register::__config();
?>
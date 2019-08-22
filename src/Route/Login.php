<?php
declare(strict_types=1);
namespace Quid\Core\Route;
use Quid\Base\Html;
use Quid\Core;

// login
abstract class Login extends Core\RouteAlias
{
	// trait
	use _nobody;
	
	
	// config
	public static $config = [
		'path'=>[null,''],
		'priority'=>998,
		'match'=>[
			'role'=>'nobody'],
		'group'=>'nobody'
	];
	
	
	// submitRoute
	// route pour soumettre le formulaire
	abstract public function submitRoute():LoginSubmit;
	
	
	// onReplace
	// comme titre, met le bootLabel
	protected function onReplace(array $return):array 
	{
		$return['title'] = $return['bootLabel'];
		
		return $return;
	}
	
	
	// submitAttr
	// attribut pour le bouton submit
	public function submitAttr() 
	{
		return;
	}
	
	
	// onBefore
	// enregistre l'uri demandé si path n'est pas empty
	protected function onBefore() 
	{
		$return = true;
		
		if(!$this->request()->isPathMatchEmpty())
		{
			$flash = $this->session()->flash();
			$redirect = $this->request()->absolute();
			$flash->set('login/redirect',$redirect);
			$return = false;
		}
		
		return $return;
	}
}

// config
Login::__config();
?>
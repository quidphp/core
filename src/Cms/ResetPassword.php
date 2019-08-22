<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Base\Html;
use Quid\Core;

// resetPassword
class ResetPassword extends Core\Route\ResetPassword
{
	// trait
	use _nobody;
	
	
	// config
	public static $config = [
		'parent'=>Login::class
	];
	
	
	// submitRoute
	// route pour soumettre le formulaire
	public function submitRoute():Core\Route\ResetPasswordSubmit
	{
		return ResetPasswordSubmit::makeOverload();
	}
	
	
	// submitAttr
	// attribut pour le bouton submit du formulaire
	public function submitAttr() 
	{
		return ['icon','padLeft','reset'];
	}
	
	
	// makeForm
	// génère le form de resetPassword
	protected function makeForm():string
	{
		$r = '';
		$route = $this->submitRoute();
		$r = $route->formOpen();
		$table = $this->db()->tables()->get('user');
		
		$r .= Html::divOp('top');
		$r .= $table->col('email')->formWrap('divtable','%:',null,['data-required'=>true]);
		$r .= Html::divClose();
		
		$r .= Html::divCond(static::langText('resetPassword/info'),'info');
		
		$r .= Html::divOp('bottom');
		$r .= Html::submit(static::label(),$this->submitAttr());
		$r .= Html::divClose();
		
		$r .= Html::formClose();
		
		return $r;
	}
	
	
	// makeButtons
	// retourne un tableau avec les boutons sous le formulaire de connexion
	protected function makeButtons():array 
	{
		$return = [];
		$return['login'] = $this->makeLogin();
		$return['register'] = $this->makeRegister();
		$return['about'] = $this->makeAbout();
		
		return $return;
	}
}

// config
ResetPassword::__config();
?>
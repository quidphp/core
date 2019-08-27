<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cms;
use Quid\Base\Html;
use Quid\Core;

// login
class Login extends Core\Route\Login
{
	// trait
	use _nobody;


	// config
	public static $config = [];


	// onReplace
	// change le titre et background de la route
	protected function onReplace(array $return):array
	{
		$return['title'] = $return['bootLabel'];
		$return['background'] = static::boot()->getOption('background');

		return $return;
	}


	// submitRoute
	// route pour soumettre le formulaire
	public function submitRoute():Core\Route\LoginSubmit
	{
		return LoginSubmit::makeOverload();
	}


	// submitAttr
	// attribut pour le bouton submit du formulaire
	public function submitAttr()
	{
		return ['icon','padLeft','login'];
	}


	// makeForm
	// génère le form du login
	protected function makeForm():string
	{
		$r = '';
		$route = $this->submitRoute();
		$r = $route->formOpen();
		$table = $this->db()->tables()->get('user');
		$session = static::session();
		$flash = $session->flash();
		$redirect = $flash->get('login/redirect');
		$username = $flash->get('login/credential') ?? $session->remember('credential');
		$usernameLabel = static::langText('login/usernameEmail');
		$remember = $flash->get('login/remember') ?? true;

		$r .= Html::inputHidden($redirect,'redirect');
		$r .= Html::divOp('top');
		$r .= $table->col('username')->formWrap('divtable',$usernameLabel.':',$username);
		$r .= $table->col('password')->formWrap('divtable','%:');
		$r .= Html::divClose();

		$r .= Html::divOp('bottom');
		$r .= Html::divOp('left');
		$r .= Html::formWrap(static::langText('login/remember'),['inputCheckbox',1,['name'=>'remember','checked'=>$remember]],'reverse');
		$r .= Html::divClose();

		$r .= Html::divOp('right');
		$r .= Html::submit(static::label(),$this->submitAttr());
		$r .= Html::divClose();
		$r .= Html::divClose();
		$r .= Html::formClose();

		return $r;
	}


	// makeButtons
	// retourne un tableau avec les boutons sous le formulaire de connexion
	protected function makeButtons():array
	{
		$return = [];
		$return['register'] = $this->makeRegister();
		$return['resetPassword'] = $this->makeResetPassword();
		$return['about'] = $this->makeAbout();

		return $return;
	}
}

// config
Login::__config();
?>
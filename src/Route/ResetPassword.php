<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Base\Html;
use Quid\Core;

// resetPassword
abstract class ResetPassword extends Core\RouteAlias
{
	// trait
	use _nobody;


	// config
	public static $config = [
		'path'=>[
			'fr'=>'mot-de-passe/reinitialisation',
			'en'=>'password/reset'],
		'match'=>[
			'role'=>'nobody',
			'session'=>'allowResetPasswordEmail'],
		'sitemap'=>false,
		'parent'=>Login::class,
		'group'=>'nobody'
	];


	// submitRoute
	// route pour soumettre le formulaire
	abstract public function submitRoute():ResetPasswordSubmit;


	// submitAttr
	// attribut pour le bouton submit
	public function submitAttr()
	{
		return;
	}


	// makeForm
	// génère le formulaire pour réinitialiser le mot de passe
	protected function makeForm():string
	{
		$r = '';
		$route = $this->submitRoute();
		$submit = static::langText('resetPassword/submit');
		$attr = $this->submitAttr();

		$r .= $route->formOpen('validate');
		$field = $this->db()->tables()->get('user')->col('email')->formPlaceholder(null,null,['data-required'=>true]);

		$r .= Html::divOp('fields');
		$r .= Html::divCond($field,'field');
		$r .= Html::divCl();

		$r .= Html::divOp('action');
		$r .= Html::submit($submit,$attr);
		$r .= Html::divCl();

		$r .= Html::formClose();

		return $r;
	}
}

// config
ResetPassword::__config();
?>
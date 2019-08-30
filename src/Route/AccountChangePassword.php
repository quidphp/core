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

// accountChangePassword
// abstract class for an account change password route
abstract class AccountChangePassword extends Core\RouteAlias
{
	// config
	public static $config = [
		'path'=>[
			'fr'=>'mon-compte/mot-de-passe',
			'en'=>'my-account/change-password'],
		'match'=>[
			'role'=>['>='=>20]],
		'group'=>'submit',
		'parent'=>Account::class,
		'colPassword'=>'password',
		'sitemap'=>false
	];


	// submitRoute
	// retourne la route pour soumettre le formulaire
	abstract public function submitRoute():AccountChangePasswordSubmit;


	// submitAttr
	// attribut pour le bouton submit
	public function submitAttr()
	{
		return;
	}


	// makeForm
	// génère le formulaire pour changer le mot de passe
	protected function makeForm():string
	{
		$r = '';
		$route = $this->submitRoute();
		$submit = static::langText('accountChangePassword/submit');
		$fields = $route::getFields();
		$colPassword = static::$config['colPassword'];
		$table = static::tableFromRowClass();
		$col = $table->col($colPassword);

		$r .= $route->formOpen('validate');
		$r .= Html::divOp('fields');

		foreach ($fields as $name)
		{
			$label = static::langText('accountChangePassword/'.$name);
			$attr = ['name'=>$name,'placeholder'=>$label,'data-required'=>true];

			$r .= Html::divOp('field');
			$r .= $col->form(null,$attr);
			$r .= Html::divCl();
		}

		$r .= Html::divCl();

		$attr = $this->submitAttr();
		$r .= Html::divOp('action');
		$r .= Html::submit($submit,$attr);
		$r .= Html::divCl();

		$r .= Html::formClose();

		return $r;
	}
}

// config
AccountChangePassword::__config();
?>
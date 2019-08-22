<?php
declare(strict_types=1);
namespace Quid\Core\Route;
use Quid\Base\Html;
use Quid\Core;

// accountChangePassword
abstract class AccountChangePassword extends Core\RouteAlias
{
	// config
	public static $config = array(
		'path'=>array(
			'fr'=>'mon-compte/mot-de-passe',
			'en'=>'my-account/change-password'),
		'match'=>array(
			'role'=>array('>='=>20)),
		'group'=>'submit',
		'parent'=>Account::class,
		'colPassword'=>'password',
		'sitemap'=>false
	);
	
	
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
			$attr = array('name'=>$name,'placeholder'=>$label,'data-required'=>true);
			
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
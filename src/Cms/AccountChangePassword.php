<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Base\Html;
use Quid\Core;
use Quid\Base;

// accountChangePassword
class AccountChangePassword extends Core\Route\AccountChangePassword
{
	// config
	public static $config = array(
		'match'=>array(
			'role'=>array('>='=>20),
			'ajax'=>true),
		'row'=>Core\Row\User::class,
		'parent'=>Account::class
	);
	
	
	// submitRoute
	// route à utiliser pour submit
	public function submitRoute():Core\Route\AccountChangePasswordSubmit
	{
		return AccountChangePasswordSubmit::makeOverload();
	}
	
	
	// submitAttr
	// attribut pour le bouton submit du formulaire
	public function submitAttr() 
	{
		return array('icon','modify','padLeft');
	}
	
	
	// trigger
	// trigge la route accountChangePassword
	public function trigger():string
	{
		$r = '';
		$r .= Html::divtableOpen();
		$r .= Html::h1($this->label());
		$r .= Html::divCond(static::langText('accountChangePassword/info'),'info');
		$r .= Html::divCond($this->makeForm(),'form');
		$r .= Html::divtableClose();
		
		return $r;
	}
	
	
	// aDialog
	// retourne le lien dialog pour ouvrir la formulaire dans une box
	public function aDialog($attr=null):string
	{
		return $this->aTitle(static::langText('accountChangePassword/link'),Base\Attr::append($attr,array('data'=>array('jsBox'=>'dialogAccountChangePassword'))));
	}
}

// config
AccountChangePassword::__config();
?>
<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Base\Html;
use Quid\Base;

// _nobody
trait _nobody
{
	// trait
	use _templateAlias;
	
	
	// config
	public static $configNobody = array(
		'docOpen'=>array(
			'body'=>array('background-image'=>'%background%'))
	);
	
	
	// makeButtons
	// fait les boutons sous le formulaire
	abstract protected function makeButtons():string;
	
	
	// onReplace
	// change le background de la route
	protected function onReplace(array $return):array 
	{
		$return['background'] = static::boot()->getOption('background');
		
		return $return;
	}
	
	
	// header
	// header seulement le logo
	public function header() 
	{
		$r = '';
		$boot = static::boot();
		$img = Html::ImgCond($boot->getOption('logo'),$boot->label());
		
		if(!empty($img))
		$r .= Html::a($boot->schemeHost(true,'app'),$img,'logo');
		
		return $r;
	}
	
	
	// main
	// fait main pour une page nobody
	public function main():string
	{
		$r = '';
		$boot = static::boot();
		
		$route = Login::makeOverload();
		$anchor = $route->a($boot->label());
		$buttons = $this->makeButtons();
		
		$r .= Html::divOp('box');
		
		$r .= Html::divOp('hgroup');
		$r .= Html::h1($anchor);
		$r .= Html::h2($boot->typeLabel());
		$r .= Html::h3(static::label());
		$r .= Html::divCl();
		
		$r .= Html::divCond($this->browscap(),'browscap');
		$r .= Html::div($this->makeForm(),'form');
		$buttons = Base\Arr::clean($buttons);
		
		if(!empty($buttons))
		{
			$class = 'amount-'.count($buttons);
			$r .= Html::divOp(array('buttons',$class));
			
			foreach ($buttons as $key => $value) 
			{
				$r .= Html::div($value,'button');
			}
			
			$r .= Html::divCl();
		}
		
		$r .= Html::divCl();
		
		return $r;
	}
	
	
	// makeAbout
	// bouton vers la page à propos
	protected function makeAbout():string 
	{
		$r = '';
		$session = static::session();
		
		if($session->can('about'))
		{
			$route = About::makeOverload();
			$r .= $route->aDialog();
		}
		
		return $r;
	}
	
	
	// makeRegister
	// bouton vers la page register, si permis
	protected function makeRegister():string 
	{
		$r = '';
		$session = static::session();
		
		if($session->allowRegister())
		{
			$route = Register::makeOverload();
			$r .= $route->aTitle();
		}
		
		return $r;
	}
	
	
	// makeLogin
	// bouton vers la page login
	protected function makeLogin():string 
	{
		$r = '';
		$route = Login::makeOverload();
		$r .= $route->aTitle();
		
		return $r;
	}
	
	
	// makeResetPassword
	// bouton pour regnérer le mot de passe
	protected function makeResetPassword():string 
	{
		$r = '';
		$session = static::session();

		if($session->allowResetPasswordEmail())
		{
			$route = ResetPassword::makeOverload();
			$r .= $route->a(static::langText('resetPassword/forgot'));
		}
		
		return $r;
	}
}
?>
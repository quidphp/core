<?php
declare(strict_types=1);
namespace Quid\Core\Route;
use Quid\Core;
use Quid\Base;

// loginSubmit
abstract class LoginSubmit extends Core\RouteAlias
{
	// trait
	use _formSubmit;
	
	
	// config
	public static $config = array(
		'path'=>array(
			'en'=>'login',
			'fr'=>'connexion'),
		'match'=>array(
			'method'=>'post',
			'role'=>'nobody'),
		'verify'=>array(
			'post'=>array('username','password'),
			'timeout'=>true,
			'genuine'=>true,
			'csrf'=>true),
		'timeout'=>array(
			'trigger'=>array('max'=>8,'timeout'=>600)),
		'parent'=>Login::class,
		'group'=>'submit'
	);
	
	
	// routeSuccessDefault
	// retourne la route vers laquelle redirigé en cas de succès par défaut, si rien dans la mémoire
	abstract public function routeSuccessDefault():Core\Route; 

	
	// onSuccess
	// callback appelé lors d'un login réussi
	protected function onSuccess():void 
	{
		static::timeoutReset('trigger');
		
		return;
	}
	
	
	// routeSuccess
	// retourne la route vers laquelle redirigé en cas de succès (si rien dans la mémoire)
	public function routeSuccess():Core\Route
	{
		$return = $this->routeSuccessMemory();
		
		if(empty($return))
		$return = $this->routeSuccessDefault();
		
		return $return;
	}
	

	// routeSuccessMemory
	// retourne la route de redirection si existante et valide
	public function routeSuccessMemory():?Core\Route 
	{
		$return = null;
		$post = $this->post();
		
		if(!empty($post['redirect']))
		{
			$routes = $this->routes();
			$request = Core\Request::newOverload($post['redirect']);
			$route = $request->route($routes);
			$role = $this->session()->role();
			
			if(!empty($route) && $route::isRedirectable($role))
			$return = $route;
		}
		
		return $return;
	}
	
	
	// routeFailure
	// retourne la route vers laquelle redirigé en cas d'erreur
	public function routeFailure():Core\Route
	{
		return static::makeParentOverload();
	}
	
	
	// proceed
	// lance l'opération de login standard
	protected function proceed():bool
	{
		$return = false;
		$session = static::session();
		$post = $this->post();
		$post = $this->onBeforeCommit($post);
		
		if($post !== null)
		{
			$remember = $post['remember'] ?? null;
			$return = $session->loginProcess($post['credential'],$post['password'],array('remember'=>$remember,'com'=>true));
		}
		
		if(empty($return))
		$this->failureComplete();
		
		else
		$this->successComplete();

		return $return;
	}
	

	// post
	// retourne les données de post pour le login
	protected function post():array 
	{
		$return = array();
		$request = $this->request();
		$return['credential'] = (string) $request->get('username');
		$return['password'] = $this->password();
		$return['remember'] = ($request->exists('remember'))? true:false;
		$return['redirect'] = $request->get('redirect');
		
		return $return;
	}
	
	
	// password
	// retourne le mot de passe
	protected function password():string
	{
		return (string) $this->request()->get('password');
	}
	
	
	// setFlash
	// ajoute les données à l'objet flash
	protected function setFlash():void 
	{
		$post = $this->post();
		$flash = static::session()->flash();
		$flash->set('login/credential',$post['credential']);
		$flash->set('login/redirect',$post['redirect']);
		$flash->set('login/remember',$post['remember']);
		
		return;
	}
}

// config
LoginSubmit::__config();
?>
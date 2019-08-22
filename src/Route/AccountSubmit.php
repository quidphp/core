<?php
declare(strict_types=1);
namespace Quid\Core\Route;
use Quid\Core;
use Quid\Base;

// accountSubmit
abstract class AccountSubmit extends Core\RouteAlias
{
	// trait
	use _formSubmit;
	
	
	// config
	public static $config = array(
		'path'=>array(
			'fr'=>'mon-compte/soumettre',
			'en'=>'my-account/submit'),
		'match'=>array(
			'method'=>'post',
			'role'=>array('>='=>20)),
		'verify'=>array(
			'post'=>array('email'),
			'timeout'=>true,
			'genuine'=>true,
			'csrf'=>true),
		'timeout'=>array(
			'failure'=>array('max'=>25,'timeout'=>600),
			'success'=>array('max'=>25,'timeout'=>600)),
		'parent'=>Account::class,
		'baseFields'=>array('email'),
		'group'=>'submit'
	);
	
	
	// onSuccess
	// callback appelé lors d'une modification réussi
	protected function onSuccess():void 
	{
		static::sessionCom()->stripFloor();
		static::timeoutIncrement('success');
		
		return;
	}
	
	
	// onFailure
	// callback appelé lors d'une modification échouée
	protected function onFailure():void 
	{
		static::timeoutIncrement('failure');
		
		return;
	}
	

	// row
	// retourne la row user
	public function row():Core\Row
	{
		return static::session()->user();
	}
	
	
	// routeSuccess
	// retourne l'objet route à rediriger en cas de succès ou erreur
	public function routeSuccess():Core\Route 
	{
		return static::makeParentOverload();
	}
	
	
	// post
	// retourne le tableau post pour la modification du compte
	public function post():array 
	{
		$return = array();
		$request = $this->request();
		$post = $request->post(true,true);
		$keep = static::getBaseFields();
		$return['data'] = Base\Arr::gets($keep,$post);
		
		return $return;
	}
	
	
	// proceed
	// lance le processus pour modifier le compte
	// retourne null ou un int
	protected function proceed():?int
	{
		$return = null;
		$row = $this->row();
		$option = $this->getOption();
		$post = $this->post();
		$post = $this->onBeforeCommit($post);
		
		if($post !== null)
		$return = $row->setUpdateChangedIncludedValid($post['data'],$option);
		
		if(is_int($return))
		$this->successComplete();
		
		else
		$this->failureComplete();
		
		return $return;
	}
	

	// getOption
	// option pour le update
	protected function getOption():?array 
	{
		return array('com'=>true);
	}
	
	
	// getBaseFields
	// retourne les champs de base
	public static function getBaseFields():array 
	{
		return static::$config['baseFields'] ?? array();
	}
}

// config
AccountSubmit::__config();
?>
<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;

// specificDispatch
class SpecificDispatch extends Core\RouteAlias
{
	// trait
	use Core\Route\_specificPrimary, Core\Segment\_table, Core\Segment\_primary;
	
	
	// config
	public static $config = array(
		'path'=>array(
			'en'=>'table/[table]/[primary]/dispatch',
			'fr'=>'table/[table]/[primary]/envoyer'),
		'segment'=>array(
			'table'=>'structureSegmentTable',
			'primary'=>'structureSegmentPrimary'),
		'match'=>array(
			'method'=>'post',
			'role'=>array('>='=>20)),
		'verify'=>array(
			'csrf'=>false,
			'genuine'=>true,
			'post'=>array('id'=>array('='=>'[primary]'),'-table-'=>array('='=>'[table]'))),
		'dispatch'=>array(
			'--modify--'=>SpecificSubmit::class,
			'--duplicate--'=>SpecificDuplicate::class,
			'--delete--'=>SpecificDelete::class,
			'--userWelcome--'=>SpecificUserWelcome::class),
		'parent'=>Specific::class,
		'group'=>'submit'
	);
	
	
	// onBefore
	// lance la route
	// comme les routes redirigent toujours, on ne devrait jamais se rendre à bootException
	protected function onBefore() 
	{
		$route = $this->getRouteLaunch();
		$route->launch();
		static::routeException(null,'specificDispatchFailed');
		
		return;
	}
	
	
	// getRouteLaunch
	// retourne la route a lancé
	protected function getRouteLaunch():Core\Route
	{
		$return = null;
		$request = $this->request();
		$post = $request->post();
		$segment = $this->segment();
		
		foreach (static::$config['dispatch'] as $key => $value) 
		{
			if(array_key_exists($key,$post) && $post[$key] === 1)
			$return = $value::makeOverload($segment);
		}
		
		if(empty($return))
		static::catchable(null,'noRedirectRoute');
		
		return $return;
	}
	
	
	// fallbackRouteRedirect
	// si c'est un failedFileUpload, renvoie vers le referer
	public function fallbackRouteRedirect($context=null)
	{
		return ($this->request()->isFailedFileUpload())? true:null;
	}
}

// config
SpecificDispatch::__config();
?>
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cms;
use Quid\Core;

// specificDispatch
// class for the specific dispatch route, directs the submit request to the proper dispatch route of the CMS
class SpecificDispatch extends Core\RouteAlias
{
	// trait
	use Core\Route\_specificPrimary;
	use Core\Segment\_table;
	use Core\Segment\_primary;


	// config
	public static $config = [
		'path'=>[
			'en'=>'table/[table]/[primary]/dispatch',
			'fr'=>'table/[table]/[primary]/envoyer'],
		'segment'=>[
			'table'=>'structureSegmentTable',
			'primary'=>'structureSegmentPrimary'],
		'match'=>[
			'method'=>'post',
			'role'=>['>='=>20]],
		'verify'=>[
			'csrf'=>false,
			'genuine'=>true,
			'post'=>['id'=>['='=>'[primary]'],'-table-'=>['='=>'[table]']]],
		'dispatch'=>[
			'--modify--'=>SpecificSubmit::class,
			'--duplicate--'=>SpecificDuplicate::class,
			'--delete--'=>SpecificDelete::class,
			'--userWelcome--'=>SpecificUserWelcome::class],
		'parent'=>Specific::class,
		'group'=>'submit'
	];


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
<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Base\Html;
use Quid\Core;

// generalTruncate
class GeneralTruncate extends Core\RouteAlias
{
	// trait
	use _common, _general, Core\Route\_formSubmit, Core\Segment\_table;
	
	
	// config
	public static $config = array(
		'path'=>array(
			'en'=>'table/[table]/truncate',
			'fr'=>'table/[table]/vider'),
		'segment'=>array(
			'table'=>'structureSegmentTable'),
		'match'=>array(
			'csrf'=>false,
			'genuine'=>true,
			'method'=>'post',
			'post'=>array('-table-'=>array('='=>'[table]')),
			'role'=>array('>='=>20)),
		'parent'=>General::class,
		'group'=>'submit'
	);
	
	
	// onBefore
	// vérifie que l'utilisateur a la permission pour truncate la table
	protected function onBefore()
	{
		$return = false;
		$table = $this->table();
		
		if(!empty($table) && $table->hasPermission('view','empty','truncate'))
		$return = true;
		
		return $return;
	}
	
	
	// routeSuccess
	// retourne la route en cas de succès ou échec du truncate
	public function routeSuccess():Core\Route
	{
		return $this->general(false);
	}
	
	
	// proceed
	// truncate la table
	protected function proceed():bool
	{
		$return = false;
		$post = $this->post();
		$post = $this->onBeforeCommit($post);
		
		if($post !== null)
		$return = $this->table()->truncate(array('com'=>true,'context'=>static::class));
		
		if(empty($return))
		$this->failureComplete();
		
		else
		$this->successComplete();
		
		return $return;
	}
}

// config
GeneralTruncate::__config();
?>
<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;

// specificDelete
class SpecificDelete extends Core\RouteAlias
{
	// trait
	use _common, _general, Core\Route\_specificPrimary, Core\Route\_formSubmit, Core\Segment\_table, Core\Segment\_primary;
	
	
	// config
	public static $config = array(
		'path'=>array(
			'en'=>'table/[table]/[primary]/delete',
			'fr'=>'table/[table]/[primary]/effacer'),
		'segment'=>array(
			'table'=>'structureSegmentTable',
			'primary'=>'structureSegmentPrimary'),
		'match'=>array(
			'csrf'=>true,
			'genuine'=>true,
			'method'=>'post',
			'post'=>array('id'=>array('='=>'[primary]'),'-table-'=>array('='=>'[table]')),
			'role'=>array('>='=>20)),
		'parent'=>Specific::class,
		'group'=>'submit'
	);
	
	
	// onBefore
	// validation avant le lancement de la route
	protected function onBefore()
	{
		$return = false;
		$table = $this->table();
		$row = $this->segment('primary');
		
		if(!empty($table) && $table->hasPermission('view','delete','remove') && !empty($row) && $row->isDeleteable())
		$return = true;
		
		return $return;
	}

	
	// routeSuccess
	// retourne la route en cas de succès ou échec de la suppression
	public function routeSuccess():Core\Route
	{
		return $this->general();
	}
	
	
	// proceed
	// efface la row
	protected function proceed():?int
	{
		$return = null;
		$post = $this->post();
		$post = $this->onBeforeCommit($post);
		
		if($post !== null)
		$return = $this->row()->delete(array('com'=>true,'context'=>static::class));
		
		if(empty($return))
		$this->failureComplete();
		
		else
		$this->successComplete();
		
		return $return;
	}
}

// config
SpecificDelete::__config();
?>
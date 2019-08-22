<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;

// specificSubmit
class SpecificSubmit extends Core\RouteAlias
{
	// trait
	use _common, _general, _specificSubmit, Core\Route\_specificPrimary, Core\Route\_formSubmit, Core\Segment\_table, Core\Segment\_primary;
	
	
	// config
	public static $config = array(
		'path'=>array(
			'en'=>'table/[table]/[primary]/submit',
			'fr'=>'table/[table]/[primary]/soumettre'),
		'segment'=>array(
			'table'=>'structureSegmentTable',
			'primary'=>'structureSegmentPrimary'),
		'match'=>array(
			'method'=>'post',
			'csrf'=>true,
			'post'=>array('id'=>array('='=>'[primary]'),'-table-'=>array('='=>'[table]')),
			'genuine'=>true,
			'role'=>array('>='=>20)),
		'response'=>array(
			'timeLimit'=>60),
		'parent'=>Specific::class,
		'group'=>'submit'
	);
	
	
	// onBefore
	// validation avant le lancement
	protected function onBefore()
	{
		$return = false;
		$table = $this->table();
		$row = $this->segment('primary');
		
		if(!empty($table) && $table->hasPermission('view','modify','update') && !empty($row) && $row->isUpdateable())
		$return = true;
		
		return $return;
	}
	
	
	// proceed
	// fait la mise à jour sur la ligne
	public function proceed():?int
	{
		$return = null;
		$post = $this->post();
		$post = $this->onBeforeCommit($post);
		
		if($post !== null)
		{
			$row = $this->row();
			$return = $row->setUpdateChangedIncludedValid($post,array('preValidate'=>true,'com'=>true,'context'=>static::class));
		}

		if(empty($return))
		$this->failureComplete();
		
		else
		$this->successComplete();
		
		return $return;
	}
}

// config
SpecificSubmit::__config();
?>
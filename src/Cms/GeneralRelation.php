<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;

// generalRelation
class GeneralRelation extends Core\RouteAlias
{
	// trait
	use _common, _general, Core\Route\_generalRelation, Core\Segment\_table, Core\Segment\_colRelation, Core\Segment\_selected, Core\Segment\_orderColRelation, Core\Segment\_page;
	
	
	// config
	public static $config = array(
		'path'=>array(
			'fr'=>'general/relation/[table]/[col]/[selected]/[order]/[page]',
			'en'=>'general/relation/[table]/[col]/[selected]/[order]/[page]'),
			'encoding'=>'structureSegmentEncoding',
		'segment'=>array(
			'table'=>'structureSegmentTable',
			'col'=>'structureSegmentColRelation',
			'selected'=>'structureSegmentSelected',
			'order'=>'structureSegmentOrderColRelation',
			'page'=>'structureSegmentPage'),
		'order'=>true,
		'match'=>array(
			'ajax'=>true,
			'role'=>array('>='=>20)),
	);
	
	
	// onBefore
	// validation avant le lancement de la route
	protected function onBefore()
	{
		$return = false;
		$table = $this->segment('table');
		$relation = $this->relation();
		
		if($table instanceof Core\Table && $table->hasPermission('view','relation','generalRelation'))
		{
			if($relation->isRelationTable())
			{
				$relationTable = $relation->relationTable();
				if($relationTable->hasPermission('relation','generalRelation'))
				$return = true;
			}
			
			else
			$return = true;
		}
		
		return $return;
	}
	
	
	// getRoute
	// retourne la route à utiliser
	protected function getRoute():Core\Route
	{
		return $this->general();
	}
}

// config
GeneralRelation::__config();
?>
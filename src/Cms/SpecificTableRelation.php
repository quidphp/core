<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Core;

// SpecificTableRelation
class SpecificTableRelation extends Core\RouteAlias
{
	// trait
	use _common, Core\Route\_tableRelation, Core\Segment\_table, Core\Segment\_orderTableRelation, Core\Segment\_page;
	
	
	// config
	public static $config = array(
		'path'=>array(
			'fr'=>'table/relation/[table]/[order]/[page]',
			'en'=>'table/relation/[table]/[order]/[page]'),
		'segment'=>array(
			'table'=>'structureSegmentTable',
			'order'=>'structureSegmentOrderTableRelation',
			'page'=>'structureSegmentPage'),
		'method'=>'tableRelationOutput',
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
		
		if($table instanceof Core\Table && $table->hasPermission('view','relation','tableRelation'))
		$return = true;
		
		return $return;
	}
}

// config
SpecificTableRelation::__config();
?>
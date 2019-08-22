<?php
declare(strict_types=1);
namespace Quid\Core\Cms;
use Quid\Base\Html;
use Quid\Core;

// specificRelation
class SpecificRelation extends Core\RouteAlias
{
	// trait
	use _common, Core\Route\_specificRelation, Core\Segment\_table, Core\Segment\_colRelation, Core\Segment\_selected, Core\Segment\_orderColRelation, Core\Segment\_page;
	
	
	// config
	public static $config = [
		'path'=>[
			'fr'=>'specifique/relation/[table]/[col]/[selected]/[order]/[page]',
			'en'=>'specific/relation/[table]/[col]/[selected]/[order]/[page]'],
		'segment'=>[
			'table'=>'structureSegmentTable',
			'col'=>'structureSegmentColRelation',
			'selected'=>'structureSegmentSelected',
			'order'=>'structureSegmentOrderColRelation',
			'page'=>'structureSegmentPage'],
		'order'=>true,
		'match'=>[
			'ajax'=>true,
			'role'=>['>='=>20]],
	];
	
	
	// onBefore
	// validation avant le lancement de la route
	protected function onBefore()
	{
		$return = false;
		$table = $this->segment('table');
		$relation = $this->relation();
		
		if($table instanceof Core\Table && $table->hasPermission('view','relation','specificRelation'))
		{
			if($relation->isRelationTable())
			{
				$relationTable = $relation->relationTable();
				if($relationTable->hasPermission('relation','specificRelation'))
				$return = true;
			}
			
			else
			$return = true;
		}
		
		return $return;
	}
}

// config
SpecificRelation::__config();
?>
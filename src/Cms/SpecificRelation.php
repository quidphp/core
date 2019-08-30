<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cms;
use Quid\Core;

// specificRelation
// class for the route which manages specific relation - enumSet inputs in the specific form
class SpecificRelation extends Core\RouteAlias
{
	// trait
	use _common;
	use Core\Route\_specificRelation;
	use Core\Segment\_table;
	use Core\Segment\_colRelation;
	use Core\Segment\_selected;
	use Core\Segment\_orderColRelation;
	use Core\Segment\_page;


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
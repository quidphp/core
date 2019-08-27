<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cms;
use Quid\Core;

// SpecificTableRelation
class SpecificTableRelation extends Core\RouteAlias
{
	// trait
	use _common; use Core\Route\_tableRelation; use Core\Segment\_table; use Core\Segment\_orderTableRelation; use Core\Segment\_page;
	// config
	public static $config = [
		'path'=>[
			'fr'=>'table/relation/[table]/[order]/[page]',
			'en'=>'table/relation/[table]/[order]/[page]'],
		'segment'=>[
			'table'=>'structureSegmentTable',
			'order'=>'structureSegmentOrderTableRelation',
			'page'=>'structureSegmentPage'],
		'method'=>'tableRelationOutput',
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

		if($table instanceof Core\Table && $table->hasPermission('view','relation','tableRelation'))
		$return = true;

		return $return;
	}
}

// config
SpecificTableRelation::__config();
?>
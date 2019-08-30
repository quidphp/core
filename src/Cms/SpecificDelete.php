<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cms;
use Quid\Core;

// specificDelete
// class for the specific delete route, to process a row deletion in the CMS
class SpecificDelete extends Core\RouteAlias
{
	// trait
	use _common;
	use _general;
	use Core\Route\_specificPrimary;
	use Core\Route\_formSubmit;
	use Core\Segment\_table;
	use Core\Segment\_primary;


	// config
	public static $config = [
		'path'=>[
			'en'=>'table/[table]/[primary]/delete',
			'fr'=>'table/[table]/[primary]/effacer'],
		'segment'=>[
			'table'=>'structureSegmentTable',
			'primary'=>'structureSegmentPrimary'],
		'match'=>[
			'csrf'=>true,
			'genuine'=>true,
			'method'=>'post',
			'post'=>['id'=>['='=>'[primary]'],'-table-'=>['='=>'[table]']],
			'role'=>['>='=>20]],
		'parent'=>Specific::class,
		'group'=>'submit'
	];


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
		$return = $this->row()->delete(['com'=>true,'context'=>static::class]);

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
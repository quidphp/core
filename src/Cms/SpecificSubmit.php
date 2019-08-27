<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cms;
use Quid\Core;

// specificSubmit
class SpecificSubmit extends Core\RouteAlias
{
	// trait
	use _common;
	use _general;
	use _specificSubmit;
	use Core\Route\_specificPrimary;
	use Core\Route\_formSubmit;
	use Core\Segment\_table;
	use Core\Segment\_primary;


	// config
	public static $config = [
		'path'=>[
			'en'=>'table/[table]/[primary]/submit',
			'fr'=>'table/[table]/[primary]/soumettre'],
		'segment'=>[
			'table'=>'structureSegmentTable',
			'primary'=>'structureSegmentPrimary'],
		'match'=>[
			'method'=>'post',
			'csrf'=>true,
			'post'=>['id'=>['='=>'[primary]'],'-table-'=>['='=>'[table]']],
			'genuine'=>true,
			'role'=>['>='=>20]],
		'response'=>[
			'timeLimit'=>60],
		'parent'=>Specific::class,
		'group'=>'submit'
	];


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
			$return = $row->setUpdateChangedIncludedValid($post,['preValidate'=>true,'com'=>true,'context'=>static::class]);
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
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;
use Quid\Orm;
use Quid\Base;

// userActive
class UserActive extends YesAlias
{
	// config
	public static $config = [];


	// formComplex
	// génère le formComplex pour userActive
	// retourne un input plain si c'est l'utilisateur courant
	public function formComplex($value=true,?array $attr=null,?array $option=null):string
	{
		$return = null;
		$session = static::boot()->session();
		$user = $session->user();

		if($value instanceof Core\Cell && $value->row()->primary() === $user->primary())
		$attr = Base\Arr::plus($attr,['tag'=>'div']);

		$return = parent::formComplex($value,$attr,$option);

		return $return;
	}


	// onSet
	// sur changement de active
	// une exception attrapable peut être envoyé
	public function onSet($value,array $row,?Orm\Cell $cell=null,array $option)
	{
		$return = null;
		$table = $this->table();
		$primary = $table->primary();
		$value = $this->value($value);
		$session = static::boot()->session();
		$user = $session->user();
		$id = $row[$primary] ?? null;
		$return = $user['active']->value();

		if(is_array($value) && !empty($value))
		$value = current($value);

		if($id === $user->primary() && $value !== $return)
		static::catchable(null,'userActiveSelf');

		else
		$return = $value;

		return $return;
	}
}

// config
UserActive::__config();
?>
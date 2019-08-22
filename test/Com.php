<?php
declare(strict_types=1);
namespace Quid\Core\Test;
use Quid\Core;
use Quid\Base;

// com
class Com extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$com = new Core\Com();

		// lang
		assert(!empty($com->neutralPrepend('Row #1',['replace'=>'ok'],"#id",['pos',"okidou"])->output()));

		return true;
	}
}
?>
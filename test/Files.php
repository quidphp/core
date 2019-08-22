<?php
declare(strict_types=1);
namespace Quid\Core\Test;
use Quid\Core;
use Quid\Base;

// files
class Files extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		Base\Dir::reset("[assertCurrent]");
		$mediaJpg = "[assertMedia]/jpg.jpg";
		$zip2 = Core\File::new("[assertCurrent]/archive2.zip",['create'=>true]);
		$image = Core\File::new($mediaJpg);
		$_file_ = Base\Finder::shortcut("[assertCommon]/class.php");
		$files2 = new Core\Files($_file_,$image);

		// zip
		$zip = $files2->zip("[assertCurrent]/zip.zip");
		assert($zip instanceof Core\File\Zip);
		assert(count($zip->all()) === 2);
		$zip3 = $files2->zip($zip2);
		assert($zip3 === $zip2);
		assert(count($zip3->all()) === 2);

		// cleanup
		Base\Dir::empty("[assertCurrent]");
		
		return true;
	}
}
?>
<?php
declare(strict_types=1);
namespace Quid\Core\File;
use Quid\Main;

// csv
class Csv extends TextAlias implements Main\Contract\Import
{
	// trait 
	use Main\File\_csv;
	
	
	// config
	public static $config = array();
}

// config
Csv::__config();
?>
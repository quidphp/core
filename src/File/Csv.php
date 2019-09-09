<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;
use Quid\Main;

// csv
// class for a csv file
class Csv extends TextAlias implements Main\Contract\Import
{
    // trait
    use Main\File\_csv;


    // config
    public static $config = [];
}

// config
Csv::__config();
?>
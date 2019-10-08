<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;
use Quid\Main;

// log
// class for a log storage file
class Log extends DumpAlias implements Main\Contract\Log, Main\Contract\FileStorage
{
    // trait
    use Main\File\_log;


    // config
    public static $config = [
        'dirname'=>'[storageLog]',
        'deleteTrim'=>50
    ];
}

// init
Log::__init();
?>
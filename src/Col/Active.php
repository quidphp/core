<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Orm;

// active
// class for the active column - a simple yes checkbox
class Active extends YesAlias
{
    // config
    public static $config = [];


    // onDuplicate
    // callback sur duplication, retourne null
    public function onDuplicate($return,array $row,Orm\Cell $cell,array $option)
    {
        return;
    }


    // classHtml
    // retourne la classe à utiliser en html pour active
    public function classHtml():string
    {
        $class = parent::class;
        return $class::className(true);
    }
}

// init
Active::__init();
?>
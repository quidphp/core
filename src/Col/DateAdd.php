<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;

// dateAdd
// class for a column which stores the current timestamp when the row is inserted
class DateAdd extends DateAlias
{
    // config
    protected static array $config = [
        'general'=>true,
        'date'=>'long',
        'visible'=>['validate'=>'notEmpty'],
        'duplicate'=>false,
        'editable'=>false,
        'complex'=>'div'
    ];


    // onInsert
    // sur insert retourne le timestamp
    final protected function onInsert($value,array $row,array $option):int
    {
        return Base\Datetime::now();
    }
}

// init
DateAdd::__init();
?>
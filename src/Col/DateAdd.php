<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Col;
use Quid\Base;

// dateAdd
// class for the dateAdd column, current timestamp is added automatically on insert
class DateAdd extends DateAlias
{
    // config
    public static $config = [
        'general'=>true,
        'date'=>'long',
        'visible'=>['validate'=>'notEmpty'],
        'duplicate'=>false,
        'editable'=>false,
        'complex'=>'div',
        'onGet'=>[[Base\Datetime::class,'onGet'],'long']
    ];


    // onInsert
    // sur insert retourne le timestamp
    final protected function onInsert($value,array $row,array $option):int
    {
        return Base\Datetime::timestamp();
    }
}

// init
DateAdd::__init();
?>
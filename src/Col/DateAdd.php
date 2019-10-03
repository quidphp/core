<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
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
        'complex'=>'div',
        'visible'=>['validate'=>'notEmpty'],
        'duplicate'=>false,
        'editable'=>false,
        'onGet'=>[[Base\Date::class,'onGet'],'long']
    ];


    // onInsert
    // sur insert retourne le timestamp
    public function onInsert($value,array $row,array $option):int
    {
        return Base\Date::timestamp();
    }
}

// init
DateAdd::__init();
?>
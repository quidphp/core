<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;
use Quid\Core;

// dateModify
// class for the dateModify column, current timestamp is updated automatically on update
class DateModify extends DateAlias
{
    // config
    public static $config = [
        'complex'=>'div',
        'visible'=>['validate'=>'notEmpty'],
        'date'=>'long',
        'duplicate'=>false,
        'onGet'=>[[Base\Date::class,'onGet'],'long'],
    ];


    // onUpdate
    // sur mise à jour, retourne le timestamp
    public function onUpdate(Core\Cell $cell,array $option):int
    {
        return Base\Date::timestamp();
    }
}

// init
DateModify::__init();
?>
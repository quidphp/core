<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;

// yes
// class for the yes column - a simple yes checkbox
class Yes extends BooleanAlias
{
    // config
    protected static array $config = [
        'complex'=>'checkbox',
        'required'=>false,
        'relation'=>'yes',
        'preValidate'=>['arrMaxCount'=>1],
        'check'=>['kind'=>'int']
    ];
}

// init
Yes::__init();
?>
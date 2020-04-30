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
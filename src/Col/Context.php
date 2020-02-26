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
use Quid\Core;

// context
// class for the context column, updates itself automatically on commit
class Context extends EnvType
{
    // config
    public static $config = [];


    // onCommit
    // ajoute le contexte sur insertion ou mise à jour
    final protected function onCommit($value,array $row,?Core\Cell $cell=null,array $option):?array
    {
        $return = null;
        $boot = static::bootReady();

        if(!empty($boot))
        $return = $boot->context();

        return $return;
    }
}

// init
Context::__init();
?>
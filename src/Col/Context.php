<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// context
// class for the context column, updates itself automatically on commit
class Context extends EnvType
{
    // config
    protected static array $config = [
        'version'=>false // inclut la version dans le contexte
    ];


    // onCommit
    // ajoute le contexte sur insertion ou mise à jour
    final protected function onCommit($value,?Core\Cell $cell=null,array $row,array $option):?array
    {
        $return = null;
        $boot = static::bootReady();
        $version = $this->getAttr('version');

        if(!empty($boot))
        $return = $boot->context($version);

        return $return;
    }
}

// init
Context::__init();
?>
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;

// html
// class for an html file
class Html extends TextAlias
{
    // config
    public static $config = [
        'group'=>'html'
    ];
}

// config
Html::__config();
?>
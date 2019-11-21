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

// contextType
// class for the contextType column, a checkbox set relation with all boot types
class ContextType extends SetAlias
{
    // config
    public static $config = [
        'required'=>true,
        'complex'=>'checkbox',
        'relation'=>'contextType'
    ];
}

// init
ContextType::__init();
?>
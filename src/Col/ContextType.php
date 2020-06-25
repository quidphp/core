<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;

// contextType
// class for the contextType column, a checkbox set relation with all boot types
class ContextType extends SetAlias
{
    // config
    protected static array $config = [
        'required'=>true,
        'complex'=>'checkbox',
        'relation'=>[self::class,'getContextType'],
        'relationSortKey'=>false
    ];


    // getContextType
    // retourne les types de contextes de boot
    final public static function getContextType():array
    {
        $return = [];
        $boot = static::boot();
        $lang = static::lang();

        foreach ($boot->types() as $type)
        {
            $return[$type] = $lang->typeLabel($type);
        }

        return $return;
    }
}

// init
ContextType::__init();
?>
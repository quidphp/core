<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;
use Quid\Base\Html;
use Quid\Core;
use Quid\Orm;

// textarea
// class for a column which is editable through a textarea input
class Textarea extends Core\ColAlias
{
    // config
    public static $config = [
        'tag'=>'textarea',
        'search'=>true,
        'check'=>['kind'=>'text'],
        'relative'=>null // custom, type pour absoluteReplace, utilise ceci pour ramener les liens absoluts dans leur version relative
    ];


    // onSet
    // gère la logique onSet pour textarea
    // la seule chose géré est le remplacement des liens absoluts pour leur version relatives
    public function onSet($return,array $row,?Orm\Cell $cell=null,array $option)
    {
        $return = parent::onSet($return,$row,$cell,$option);

        if(is_string($return))
        $return = $this->absoluteReplace($return);

        return $return;
    }


    // absoluteReplace
    // remplacement des liens absoluts vers relatifs dans le bloc texte
    protected function absoluteReplace(string $return):string
    {
        $relative = $this->attr('relative');

        if(!empty($relative))
        {
            $relative = (array) $relative;
            $boot = static::boot();
            $replace = [];

            foreach ($relative as $type)
            {
                foreach ($boot->schemeHostEnvs($type) as $schemeHost)
                {
                    $schemeHost .= '/';
                    $replace[$schemeHost] = '/';
                }
            }

            if(!empty($replace))
            $return = Base\Str::replace($replace,$return);
        }

        return $return;
    }
}

// init
Textarea::__init();
?>
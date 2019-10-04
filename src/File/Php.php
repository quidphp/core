<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;
use Quid\Core;

// php
// class for a php file
class Php extends TextAlias
{
    // config
    public static $config = [
        'group'=>'php'
    ];
    
    
    // concatenateMany
    // permet de concatener du php à partir de namespace
    // ceci ne peut pas être fait si le autoload est en mode preload
    public static function concatenateMany(array $array):Core\Files
    {
        $return = Core\Files::newOverload();

        foreach ($array as $arr)
        {
            if(is_array($arr) && count($arr) === 2)
            {
                $target = $arr['target'] ?? null;
                $option = $arr['option'] ?? null;

                if(!empty($target))
                {
                    $service = Core\Service\PhpConcatenator::class;
                    $target = Core\File::newCreate($target);
                    if($target instanceof self)
                    {
                        $compiler = new $service(__METHOD__,$option);
                        $target = $compiler->triggerWrite($target);
                        $return->add($target);
                    }
                }
            }
        }

        return $return;
    }
}

// init
Php::__init();
?>
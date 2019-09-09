<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Service;
use Quid\Core;
use JShrink\Minifier;

// jShrink
// class that provides methods to use tedivm/jshrink for minifying JavaScript
class JShrink extends Core\ServiceAlias
{
    // config
    public static $config = [
        'option'=>[
            'flaggedComments'=>false]
    ];


    // trigger
    // permet de faire un minify d'une string js fourni en argument
    // retourne le js minify
    public function trigger(string $value):string
    {
        return Minifier::minify($value,$this->option());
    }


    // staticTrigger
    // méthode statique pour créer l'objet et minify la string
    // retourne une string
    public static function staticTrigger(string $value,?array $option=null):string
    {
        $return = null;
        $minifier = new static(__METHOD__,$option);
        $return = $minifier->trigger($value);

        return $return;
    }
}

// config
JShrink::__config();
?>
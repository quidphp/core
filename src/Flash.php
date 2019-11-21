<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core;
use Quid\Routing;

// flash
// extended class for a collection containing flash-like data (delete on read)
class Flash extends Routing\Flash
{
    // trait
    use _bootAccess;


    // config
    public static $config = [];


    // setPost
    // flash les données de post
    // prend les données de post de l'objet request dans inst
    final public function setPost(Route $route,bool $onlyCol=true,bool $stripTags=false)
    {
        $request = static::boot()->request();
        $post = $request->post($onlyCol,$stripTags);
        $this->set($route,$post);

        return $this;
    }
}
?>
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Main;

// roles
// extended class for a collection containing many roles
class Roles extends Main\Roles
{
    // trait
    use _fullAccess;


    // init
    // init l'objet roles
    // simplement un sort default
    public function init(string $type):self
    {
        $this->sortDefault();

        return $this;
    }
}

// config
Roles::__config();
?>
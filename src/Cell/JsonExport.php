<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cell;
use Quid\Core;

// jsonExport
// class for a cell that contains json which should be exported (similar to var_export)
class JsonExport extends Core\CellAlias
{
    // config
    public static $config = [];


    // isInvalidValue
    // retourne vrai si la valeur est invalide, donc trop longue pour la colonne
    public function isInvalidValue():bool
    {
        return ($this->get() === $this->col()->invalidValue())? true:false;
    }
}

// init
JsonExport::__init();
?>
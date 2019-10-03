<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Base;

// jsonExport
// class for a column that contains json which should be exported (similar to var_export)
class JsonExport extends JsonAlias
{
    // config
    public static $config = [
        'complex'=>'div',
        'onComplex'=>true,
        'visible'=>['validate'=>'notEmpty']
    ];


    // varExport
    // permet d'envoyer un array dans var export
    public static function varExport(array $return)
    {
        return Base\Debug::export($return);
    }
}

// init
JsonExport::__init();
?>
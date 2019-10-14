<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;
use Quid\Orm;
use Quid\Base;

// jsonExport
// class for a column that contains json which should be exported (similar to var_export)
class JsonExport extends JsonAlias
{
    // config
    public static $config = [
        'cell'=>Core\Cell\JsonExport::class,
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
    
    
    // onSet
    // gère la logique onSet pour jsonExport
    // si la valeur est trop longue, n'envoie pas d'erreur mais retourne un tableau avec la clé incomplete à true
    public function onSet($return,array $row,?Orm\Cell $cell=null,array $option)
    {
        $return = parent::onSet($return,$row,$cell,$option);
        
        if(!empty($return) && $this->validate($return) !== true)
        $return = parent::onSet(static::invalidValue(),$row,$cell,$option);
        
        return $return;
    }
    
    
    // invalidValue
    // retourne la valeur pour représenter que la valeur enregistrée est invalide (trop longue)
    public static function invalidValue():array
    {
        return ['incomplete'=>true];
    }
}

// init
JsonExport::__init();
?>
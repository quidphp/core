<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core\Row;
use Quid\Core;

// redirection
// class to work with a row of the redirection table
class Redirection extends Core\RowAlias
{
    // config
    protected static array $config = [
        'panel'=>false,
        'key'=>'key',
        'order'=>['id'=>'desc'],
        'parent'=>'system',
        'priority'=>952,
        'searchMinLength'=>1,
        'deleteAutoIncrement'=>true,
        'cols'=>[
            'active'=>true,
            'type'=>['class'=>Core\Col\ContextType::class],
            'key'=>['general'=>true],
            'value'=>['general'=>true,'required'=>true]]
    ];


    // grabContent
    // retourne un tableau de tous les contenus de redirection pertinente
    // il faut fournir un un type (index)
    final public static function grabContent(int $type):array
    {
        $return = [];
        $table = static::tableFromFqcn();
        $typeCol = $table->col('type');
        $keyCol = $table->colKey();
        $valueCol = $table->col('value');
        $where = [true,[$typeCol,'findInSet',$type]];
        $return = $table->keyValue($keyCol,$valueCol,false,$where);

        return $return;
    }
}

// init
Redirection::__init();
?>
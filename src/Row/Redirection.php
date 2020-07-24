<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
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


    // getCacheIdentifier
    // retourne l'identificateur de cache
    final public static function getCacheIdentifier(string $type):array
    {
        $return = [];
        $boot = static::boot();
        $table = static::tableFromFqcn();
        $return = [$table->name(),$type,$boot->version()];

        return $return;
    }


    // onCommittedOrDeleted
    // sur insert, update ou delete efface la cache de tous les types
    final protected function onCommittedOrDeleted(array $option)
    {
        parent::onCommittedOrDeleted($option);

        $boot = static::boot();

        foreach ($boot->types() as $type)
        {
            $identifier = static::getCacheIdentifier($type);
            static::cacheFile($identifier,null);
        }

        return $this;
    }


    // grabContent
    // retourne un tableau de tous les contenus de redirection pertinente
    // il faut fournir un un type (index)
    final public static function grabContent(int $type):array
    {
        $boot = static::boot();
        $identifier = static::getCacheIdentifier($boot->type());

        return static::cacheFile($identifier,function() use($type) {
            $table = static::tableFromFqcn();
            $typeCol = $table->col('type');
            $keyCol = $table->colKey();
            $valueCol = $table->col('value');
            $where = [true,[$typeCol,'findInSet',$type]];

            return $table->keyValue($keyCol,$valueCol,false,$where);
        },$boot->shouldCache());
    }
}

// init
Redirection::__init();
?>
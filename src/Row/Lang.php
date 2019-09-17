<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Row;
use Quid\Base;
use Quid\Core;

// lang
// class to work with a row of the lang table, contains the text and translations
class Lang extends Core\RowAlias
{
    // config
    public static $config = [
        'panel'=>false,
        'key'=>'key',
        'order'=>['id'=>'desc'],
        'parent'=>'system',
        'priority'=>950,
        'searchMinLength'=>1,
        'cols'=>[
            'active'=>true,
            'type'=>['class'=>Core\Col\ContextType::class],
            'key'=>true,
            'content_fr'=>['general'=>true,'class'=>Core\Col\Textarea::class,'required'=>true,'exists'=>false],
            'content_en'=>['general'=>true,'class'=>Core\Col\Textarea::class,'required'=>true,'exists'=>false]]
    ];


    // grabContent
    // retourne un tableau de tous les contenus de langue pertinente
    // il faut fournir un code de langue et un type
    public static function grabContent(string $value,string $type):array
    {
        $return = [];
        $table = static::tableFromFqcn();
        $typeCol = $table->col('type');
        $keyCol = $table->colKey();
        $contentCol = $table->col("content_$value");
        $where = [true,[$typeCol,'findInSet',$type]];
        $return = $table->keyValue($keyCol,$contentCol,false,$where);

        if(!empty($return))
        $return = Base\Lang::content($return);

        return $return;
    }


    // import
    // permet d'importer du contenu dans la table lang
    public static function import(array $array,string $lang,$types=null):array
    {
        $return = [];
        $table = static::tableFromFqcn();
        $col = $table->col('content_'.$lang);
        $colName = $col->name();
        $array = Base\Arrs::crush($array);

        foreach ($array as $key => $value)
        {
            if(is_string($key) && is_scalar($value))
            {
                $row = $table->row($key);

                if(empty($row))
                {
                    $set = [];
                    $set['type'] = $types;
                    $set['key'] = $key;
                    $set[$colName] = (string) $value;
                    $save = $table->insert($set,['row'=>false]);
                    $return[$key] = $save;
                }
            }
        }

        return $return;
    }
}

// config
Lang::__config();
?>
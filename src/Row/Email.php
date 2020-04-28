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
use Quid\Main;

// email
// class to deal with a row of the email table, contains the emailModels
class Email extends Core\RowAlias implements Main\Contract\Email
{
    // trait
    use Main\_email;


    // config
    protected static array $config = [
        'panel'=>false,
        'priority'=>951,
        'parent'=>'system',
        'cols'=>[
            'active'=>true,
            'type'=>['class'=>Core\Col\ContextType::class],
            'contentType'=>['relation'=>'emailType','default'=>1],
            'key'=>true,
            'name_fr'=>false,
            'name_en'=>false,
            'content_fr'=>['exists'=>false],
            'content_en'=>['exists'=>false]]
    ];


    // isText
    // retourne vrai si le contentType est texte
    final public function isText():bool
    {
        return $this->contentType() === 1;
    }


    // isHtml
    // retourne vrai si le contentType est html
    final public function isHtml():bool
    {
        return $this->contentType() === 2;
    }


    // getKey
    // retourne la clé du modèle
    final public function getKey():string
    {
        return $this->cellKey()->value();
    }


    // contentType
    // retourne le type de contenu du email, retourne int
    final public function contentType():int
    {
        return $this->cell('contentType')->get();
    }


    // subject
    // retourne le sujet du email
    final public function subject():string
    {
        return $this->cell('name_[lang]')->get();
    }


    // body
    // retourne le body du email
    final public function body():string
    {
        return $this->cell('content_[lang]')->get();
    }


    // find
    // retourne un objet email, à partir d'une clé
    // le type doit être fourni, si true utilise type courant
    // le modèle doit être actif
    final public static function find(string $key,$type=true):?self
    {
        $return = null;

        if($type === true)
        $type = null;

        $type = static::boot()->typeIndex($type);

        $table = static::tableFromFqcn();
        $where = [];
        $where[] = [$table->colKey(),'=',$key];
        $where[] = ['type','=',$type];

        $row = static::tableFromFqcn()->row($where);
        if(!empty($row) && $row->isActive())
        $return = $row;

        return $return;
    }
}

// init
Email::__init();
?>
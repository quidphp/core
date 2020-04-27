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
            'type'=>['relation'=>'emailType','default'=>1],
            'key'=>true,
            'name_fr'=>false,
            'name_en'=>false,
            'content_fr'=>['exists'=>false],
            'content_en'=>['exists'=>false]]
    ];


    // contentType
    // retourne le type de contenu du email, retourne int
    final public function contentType():int
    {
        return $this->cell('type')->get();
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
    // doit être actif
    final public static function find(string $key):?self
    {
        $return = null;
        $row = static::tableFromFqcn()->row($key);

        if(!empty($row) && $row->isActive())
        $return = $row;

        return $return;
    }
}

// init
Email::__init();
?>
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Base;
use Quid\Orm;
use Quid\Routing;

// col
// extended class to represent an existing column within a table
class Col extends Orm\Col
{
    // trait
    use _accessAlias;
    use Routing\_attrRoute;


    // config
    protected static array $config = [
        'route'=>null, // permet de définir la route à utiliser en lien avec complex
        'detailMaxLength'=>true, // affiche le max length dans detail
        'detailPreValidate'=>false, // affiche la prévalidation dans detail
        'detailValidate'=>true, // affiche la validation dans détail
        'generalExcerptMin'=>null // excerpt min pour l'affichage dans general
    ];


    // details
    // retourne un tableau de détail en lien avec la colonne
    // les détails sont pour la plupart généré automatiquement
    public function details(bool $lang=true):array
    {
        $return = [];

        if($this->isRequired())
        {
            $required = $this->ruleRequired($lang);
            if(!empty($required))
            $return[] = $required;
        }

        if($this->shouldBeUnique())
        {
            $unique = $this->ruleUnique($lang);
            if(!empty($unique))
            $return[] = $unique;
        }

        if($this->getAttr('detailMaxLength') === true && is_int($this->length()))
        {
            $maxLength = $this->ruleMaxLength($lang);
            if(!empty($maxLength))
            $return[] = $maxLength;
        }

        if($this->getAttr('detailPreValidate'))
        {
            $preValidate = $this->rulePreValidate($lang);
            $return = Base\Arr::merge($return,$preValidate);
        }

        if($this->getAttr('detailValidate'))
        {
            $validate = $this->ruleValidate($lang);
            if(!empty($validate))
            $return = Base\Arr::merge($return,$validate);
        }

        return $return;
    }


    // generalExcerptMin
    // retourne la longueur de l'excerpt pour general
    final public function generalExcerptMin():?int
    {
        return $this->getAttr('generalExcerptMin');
    }


    // generalCurrentLang
    // retourne vrai si le nom de la colonne a un pattern de la langue courante
    final public static function generalCurrentLang(self $col):bool
    {
        $return = false;
        $boot = static::boot();
        $langCode = $col->schema()->nameLangCode();

        if($boot->lang()->currentLang() === $langCode)
        $return = true;

        return $return;
    }
}

// init
Col::__init();
?>
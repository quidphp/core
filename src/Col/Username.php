<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// username
// class for the username column of a user row
class Username extends Core\ColAlias
{
    // config
    public static $config = [
        'general'=>true,
        'required'=>true,
        'check'=>['kind'=>'char'],
        'security'=>null // custom, défini le niveau de sécurité du mot de passe utilisé, support pour loose
    ];


    // onMakeAttr
    // callback lors du set des attr
    // permet de charger le niveau de sécurité du username
    protected function onMakeAttr(array $return):array
    {
        $return['validate'] = $return['validate'] ?? [];
        $security = $return['security'] ?? null;
        $originalValidate = 'username';
        $validate = $originalValidate;

        if(is_string($security))
        {
            $security = ucfirst($security);
            $validate .= $security;
        }

        if(!in_array($originalValidate,$return['validate'],true) && !in_array($security,$return['validate'],true))
        $return['validate'][] = $validate;

        return $return;
    }


    // getSecurity
    // retourne le niveau de sécurité du mot de passe
    public function getSecurity():?string
    {
        return $this->attr('security');
    }
}

// init
Username::__init();
?>
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Core;

// login
// abstract class for a login route
abstract class Login extends Core\RouteAlias
{
    // trait
    use _nobody;


    // config
    public static $config = [
        'path'=>[
            'en'=>'login',
            'fr'=>'connexion'],
        'priority'=>998,
        'match'=>[
            'role'=>'nobody'],
        'group'=>'nobody'
    ];


    // submitRoute
    // route pour soumettre le formulaire
    abstract public function submitRoute():LoginSubmit;


    // onReplace
    // comme titre, met le bootLabel
    protected function onReplace(array $return):array
    {
        $return['title'] = $return['bootLabel'];

        return $return;
    }


    // submitAttr
    // attribut pour le bouton submit
    public function submitAttr()
    {
        return;
    }
}

// init
Login::__init();
?>
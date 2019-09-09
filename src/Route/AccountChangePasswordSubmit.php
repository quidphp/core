<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Core;

// accountChangePasswordSubmit
// abstract class for an account change password submit route
abstract class AccountChangePasswordSubmit extends Core\RouteAlias
{
    // trait
    use _formSubmit;


    // config
    public static $config = [
        'path'=>[
            'fr'=>'mon-compte/mot-de-passe/soumettre',
            'en'=>'my-account/change-password/submit'],
        'match'=>[
            'method'=>'post',
            'role'=>['>='=>20]],
        'verify'=>[
            'post'=>['oldPassword','newPassword','newPasswordConfirm'],
            'genuine'=>true,
            'csrf'=>true],
        'parent'=>AccountChangePassword::class,
        'group'=>'submit'
    ];


    // onFailure
    // callback appelé lors d'un ajout avec erreur
    protected function onFailure():void
    {
        $com = static::session()->com();
        $com->keepCeiling();

        return;
    }


    // routeSuccess
    // retourne l'objet route pour la redirection
    public function routeSuccess():Core\Route
    {
        return static::makeParentOverload();
    }


    // proceed
    // procède au changement de mot de passe
    public function proceed():bool
    {
        $return = false;
        $session = static::session();
        $post = $this->post();
        $post = $this->onBeforeCommit($post);

        if($post !== null)
        $return = $session->changePassword($post['newPassword'],$post['newPasswordConfirm'],$post['oldPassword'],['com'=>true]);

        if(empty($return))
        $this->failureComplete();

        else
        $this->successComplete();

        return $return;
    }


    // post
    // retourne les données post pour le changement de mot de passe compte
    protected function post():array
    {
        $return = [];
        $request = $this->request();

        foreach (static::getFields() as $value)
        {
            if(is_string($value))
            $return[$value] = (string) $request->get($value);
        }

        return $return;
    }


    // getFields
    // retourne le nom des champs pour le formulaire
    public static function getFields():array
    {
        return static::$config['verify']['post'] ?? [];
    }
}

// config
AccountChangePasswordSubmit::__config();
?>
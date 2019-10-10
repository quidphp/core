<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Base;
use Quid\Core;
use Quid\Main;

// registerSubmit
// abstract class for a register submit route
abstract class RegisterSubmit extends Core\RouteAlias
{
    // trait
    use _formSubmit;


    // config
    public static $config = [
        'path'=>[
            'fr'=>'enregistrement/soumettre',
            'en'=>'register/submit'],
        'match'=>[
            'method'=>'post',
            'role'=>'nobody',
            'session'=>'allowRegister',
            'post'=>['username','email','password','passwordConfirm'],
            'timeout'=>true,
            'csrf'=>true,
            'genuine'=>true],
        'timeout'=>[
            'failure'=>['max'=>12,'timeout'=>600],
            'success'=>['max'=>2,'timeout'=>600]],
        'parent'=>Register::class,
        'dataDefault'=>['active'=>null],
        'baseFields'=>['username','email'],
        'passwordFields'=>[
            'password'=>'password',
            'passwordConfirm'=>'passwordConfirm'],
        'group'=>'submit',
        'flashPost'=>true
    ];


    // onSuccess
    // traite le succès
    protected function onSuccess():void
    {
        static::sessionCom()->stripFloor();
        static::timeoutIncrement('success');

        return;
    }


    // onFailure
    // increment le timeout et appele onFailure
    protected function onFailure():void
    {
        static::sessionCom()->stripFloor();
        static::timeoutIncrement('failure');

        return;
    }


    // routeSuccess
    // méthode abstraite, retourne l'objet route en cas de succès
    protected function routeSuccess():Core\Route
    {
        return Login::makeOverload();
    }


    // routeFailure
    // retourne l'objet route pour la redirection en cas d'erreur
    protected function routeFailure():Core\Route
    {
        return static::makeParentOverload();
    }


    // post
    // retourne le tableau post pour l'enregistrement
    public function post():array
    {
        $return = [];
        $request = $this->request();
        $post = $request->post(true,true);
        $password = static::getPasswordFields();
        $default = static::getDataDefault($post);
        $passwordConfirm = $password['passwordConfirm'];
        $keep = static::getBaseFields();
        $keep[] = $password['password'];

        $return['data'] = Base\Arr::gets($keep,$post);
        $return['data'] = Base\Arr::replace($return['data'],$default);
        $return['passwordConfirm'] = $post[$passwordConfirm];

        return $return;
    }


    // proceed
    // lance le processus pour register
    // retourne null ou un objet user
    protected function proceed():?Main\Contract\User
    {
        $return = null;
        $session = static::session();
        $class = $session->getUserClass();
        $option = $this->getOption();
        $post = $this->post();
        $post = $this->onBeforeCommit($post);

        if($post !== null)
        $return = $class::registerProcess($post['data'],$post['passwordConfirm'],$option);

        if(empty($return))
        $this->failureComplete();

        else
        $this->successComplete();

        return $return;
    }


    // getOption
    // option pour le reset password
    protected function getOption():?array
    {
        return ['com'=>true];
    }


    // getDataDefault
    // retourne les valeurs data par défaut, écrase ce qu'il y a dans post
    // possible de lier une callable à une clé
    public static function getDataDefault(array $data):array
    {
        $return = static::$config['dataDefault'] ?? [];

        foreach ($return as $key => $value)
        {
            if(is_array($value) && static::classIsCallable($value))
            $return[$key] = $value($data);
        }

        return $return;
    }


    // getBaseFields
    // retourne les champs de base
    public static function getBaseFields():array
    {
        return static::$config['baseFields'] ?? [];
    }


    // getPasswordFields
    // retourne les champs de mot de passe
    public static function getPasswordFields():array
    {
        return static::$config['passwordFields'] ?? [];
    }
}

// init
RegisterSubmit::__init();
?>
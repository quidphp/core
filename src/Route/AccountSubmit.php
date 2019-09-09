<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Core;
use Quid\Base;

// accountSubmit
// abstract class for an account submit route
abstract class AccountSubmit extends Core\RouteAlias
{
    // trait
    use _formSubmit;


    // config
    public static $config = [
        'path'=>[
            'fr'=>'mon-compte/soumettre',
            'en'=>'my-account/submit'],
        'match'=>[
            'method'=>'post',
            'role'=>['>='=>20]],
        'verify'=>[
            'post'=>['email'],
            'timeout'=>true,
            'genuine'=>true,
            'csrf'=>true],
        'timeout'=>[
            'failure'=>['max'=>25,'timeout'=>600],
            'success'=>['max'=>25,'timeout'=>600]],
        'parent'=>Account::class,
        'baseFields'=>['email'],
        'group'=>'submit'
    ];


    // onSuccess
    // callback appelé lors d'une modification réussi
    protected function onSuccess():void
    {
        static::sessionCom()->stripFloor();
        static::timeoutIncrement('success');

        return;
    }


    // onFailure
    // callback appelé lors d'une modification échouée
    protected function onFailure():void
    {
        static::timeoutIncrement('failure');

        return;
    }


    // row
    // retourne la row user
    public function row():Core\Row
    {
        return static::session()->user();
    }


    // routeSuccess
    // retourne l'objet route à rediriger en cas de succès ou erreur
    public function routeSuccess():Core\Route
    {
        return static::makeParentOverload();
    }


    // post
    // retourne le tableau post pour la modification du compte
    public function post():array
    {
        $return = [];
        $request = $this->request();
        $post = $request->post(true,true);
        $keep = static::getBaseFields();
        $return['data'] = Base\Arr::gets($keep,$post);

        return $return;
    }


    // proceed
    // lance le processus pour modifier le compte
    // retourne null ou un int
    protected function proceed():?int
    {
        $return = null;
        $row = $this->row();
        $option = $this->getOption();
        $post = $this->post();
        $post = $this->onBeforeCommit($post);

        if($post !== null)
        $return = $row->setUpdateChangedIncludedValid($post['data'],$option);

        if(is_int($return))
        $this->successComplete();

        else
        $this->failureComplete();

        return $return;
    }


    // getOption
    // option pour le update
    protected function getOption():?array
    {
        return ['com'=>true];
    }


    // getBaseFields
    // retourne les champs de base
    public static function getBaseFields():array
    {
        return static::$config['baseFields'] ?? [];
    }
}

// config
AccountSubmit::__config();
?>
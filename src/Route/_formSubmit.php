<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;

// _formSubmit
// trait that provides methods and logic necessary to make a form submit route
trait _formSubmit
{
    // dynamique
    protected $success = false; // défini si l'utilisateur a soumis le formulaire avec succès


    // routeSuccess
    // méthode abstraite, retourne l'objet route à rediriger
    // peut aussi retoruner une string
    abstract protected function routeSuccess();


    // onFallbackRedirect
    // sur redirection lors du fallback, set flash
    protected function onFallbackRedirect()
    {
        $this->setFlash();

        return;
    }


    // onSuccess
    // callback appelé lors d'un succès
    protected function onSuccess():void
    {
        return;
    }


    // onFailure
    // callback appelé lors d'un échec
    protected function onFailure():void
    {
        return;
    }


    // onBeforeCommit
    // permet de faire des changements au tableau post avant le commit
    // si on retourne null, le commit est annulé
    protected function onBeforeCommit(array $return):?array
    {
        return $return;
    }


    // successComplete
    // traite le succès
    protected function successComplete():void
    {
        $this->setSuccess();
        $this->onSuccess();

        return;
    }


    // failureComplete
    // traite l'échec
    protected function failureComplete():void
    {
        $this->setFlash();
        $this->onFailure();

        return;
    }


    // routeFailure
    // retourne l'objet route en cas d'erreur, par défaut renvoie à success
    // peut aussi retoruner une string
    protected function routeFailure()
    {
        return $this->routeSuccess();
    }


    // afterRouteRedirect
    // retourne la route vers laquelle redirigé, différent si c'est succès ou non
    public function afterRouteRedirect()
    {
        $return = null;

        if($this->isSuccess())
        $return = $this->routeSuccess();

        else
        $return = $this->routeFailure();

        return $return;
    }


    // isSuccess
    // retourne vrai si le formulaire est un succès
    public function isSuccess()
    {
        return ($this->success === true)? true:false;
    }


    // setSuccess
    // permet d'attribuer une valeur à la propriété success
    public function setSuccess(bool $value=true):self
    {
        $this->success = $value;

        return $this;
    }


    // setFlash
    // conserve les données flash, par défaut utilise la méthode flashPost de session
    protected function setFlash():void
    {
        $this->session()->flashPost($this);

        return;
    }


    // post
    // retourne le tableau post pour le formulaire
    protected function post():array
    {
        return $this->request()->post(true,false,true);
    }


    // trigger
    // lance la méthode proceed et retoure null
    public function trigger()
    {
        $this->proceed();

        return;
    }
}
?>
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Row;
use Quid\Base;
use Quid\Main;

// _emailModel
// trait with methods relative to sending emails from models
trait _emailModel
{
    // onEmailSent
    // callback lorsqu'un courriel de confirmation a été envoyé
    final protected function onEmailSent(Email $model):void
    {
        return;
    }


    // hasEmailModel
    // retourne vrai si le modèle de courriel existe
    final public function hasEmailModel(string $name,$type=true):bool
    {
        return !empty($this->getEmailModel($name,$type));
    }


    // getEmailModel
    // retourne un modèle de courriel ou null
    // si la clé est défini dans les attributs, remplace sinon utilise celle donné
    final public function getEmailModel(string $key,$type=true):?Main\Contract\Email
    {
        return static::getEmailModelStatic($key,$type);
    }


    // getEmailArray
    // retourne le tableau pour envoyer un courriel en lien avec l'utilisateur
    // peut retourner null
    final protected function getEmailArray(string $name,$type=true,?array $replace=null):array
    {
        $model = $this->getEmailModel($name,$type);

        if(empty($model))
        static::throw('modelNotFound',$name);

        if(empty($replace))
        static::throw('modelNoReplace',$replace);

        return ['model'=>$model,'replace'=>$replace];
    }


    // prepareEmailReplace
    // méthode qui prépare le remplacement pour les courriels
    final protected function prepareEmailReplace(?array ...$values):array
    {
        $return = static::boot()->getReplace();
        $return = Base\Arr::replace($return,$this->getEmailReplace());

        if(!empty($values))
        $return = Base\Arr::replace($return,...$values);

        return $return;
    }


    // getEmailReplace
    // retourne un tableau de remplacement pour tous les courriels de la row
    protected function getEmailReplace():array
    {
        return [];
    }


    // getEmailLang
    // retourne la langue à utiliser pour le courriel
    protected function getEmailLang(Main\Contract\Email $model):?string
    {
        return null;
    }


    // sendEmail
    // méthode utilisé par différentes méthodes d'envoies de courriels
    // une closure peut être donné en argument de array pour faire les derniers remplacements avant l'envoie du courriel
    final protected function sendEmail(array $array,$to,?array $option=null):bool
    {
        $return = false;
        $option = Base\Arr::plus(['key'=>null,'method'=>'dispatch'],$option);

        if(empty($array) || empty($to))
        static::throw('cannotSendEmail',$type);

        $model = $array['model'];
        $this->validateSend($model,$option);
        $key = $option['key'];
        $method = $option['method'];
        $replace = $array['replace'];

        $closure = $array['closure'] ?? null;
        if(!empty($closure))
        $replace = $closure($replace);

        $lang = $this->getEmailLang($model);
        $return = $model->$method($key,$to,$replace,$lang);

        if($return === true)
        $this->onEmailSent($model);

        return $return;
    }


    // validateSend
    // méthode protégé qui valide qui permet de valider si la row et le modèle sont valides
    final protected function validateSend(Main\Contract\Email $model,?array $option=null):void
    {
        $this->validateSendModel($model,$option);
    }


    // validateSendModel
    // méthode protégé qui valide que le modèle de courriel sont valides
    final protected function validateSendModel(Main\Contract\Email $model,?array $option=null):void
    {
        $option = Base\Arr::plus(['method'=>'dispatch'],$option);

        if(!$model->isActive())
        static::throw('invalidEmailModel');

        if(!is_string($option['method']) || !$model->hasMethod($option['method']))
        static::throw('invalidMethod',$option['method']);
    }


    // getAdminEmail
    // retourne le email du premier utilisateur administrateur
    // renvoie à boot
    public static function getAdminEmail():?array
    {
        return static::boot()->getAdminEmail();
    }


    // hasEmailModelStatic
    // retourne vrai si un modèle de courriel existe statiquement
    final public static function hasEmailModelStatic(string $key,$type=true):bool
    {
        return !empty(static::getEmailModelStatic($key,$type));
    }


    // getEmailModelStatic
    // permet de retourner statiquement un modèle
    final public static function getEmailModelStatic(string $key,$type=true):?Main\Contract\Email
    {
        $key = static::$config['emailModel'][$key] ?? $key;
        return Email::find($key,$type);
    }
}
?>
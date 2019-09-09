<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;
use Quid\Orm;
use Quid\Base;

// userPassword
// class for the column which manages the password field for the user row
class UserPassword extends Core\ColAlias
{
    // config
    public static $config = [
        'tag'=>'inputPassword',
        'preValidate'=>['arrCount'=>2],
        'required'=>true,
        'search'=>false,
        'export'=>false,
        'visible'=>[
            'session'=>'isPasswordVisible',
            'row'=>'isUpdateable'],
        'visibleGeneral'=>false,
        'check'=>['kind'=>'char'],
        'log'=>Core\Row\Log::class, // custom
        'security'=>null // défini le niveau de sécurité du mot de passe utilisé, support pour loose
    ];


    // onMakeAttr
    // callback lors du set des attr
    // permet de charger le niveau de sécurité du mot de passe
    protected function onMakeAttr(array $return):array
    {
        $return['pattern'] = $return['pattern'] ?? null;
        $return['validate'] = $return['validate'] ?? [];
        $security = $return['security'] ?? null;
        $originalValidate = 'passwordHash';
        $validate = $originalValidate;
        $originalPattern = 'password';
        $pattern = $originalPattern;

        if(is_string($security))
        {
            $security = ucfirst($security);
            $validate .= $security;
            $pattern .= $security;
        }

        if(!in_array($originalValidate,$return['validate'],true) && !in_array($validate,$return['validate'],true))
        $return['validate'][] = $validate;

        if(!in_array($return['pattern'],[$originalPattern,$pattern],true))
        $return['pattern'] = $pattern;

        return $return;
    }


    // preValidatePrepare
    // prépare la valeur en provenance de post avant la prévalidation
    public function preValidatePrepare($value)
    {
        $return = null;

        if(is_array($value))
        {
            $clean = Base\Arr::clean($value);

            if(!empty($clean))
            $return = $clean;
        }

        return $return;
    }


    // onSet
    // logique onSet pour le champ password
    // possible de fournir un mot de passe via un array ou une string
    public function onSet($value,array $row,?Orm\Cell $cell=null,array $option)
    {
        $return = null;

        if(is_array($value))
        {
            $table = $this->table();
            $hashOption = $table->attr('crypt/passwordHash');
            $security = $this->getSecurity();

            $newPassword = Base\Arr::index(0,$value);
            $newPasswordConfirm = Base\Arr::index(1,$value);
            $oldPassword = Base\Arr::index(2,$value);
            $oldPasswordHash = (!empty($cell))? $cell->get():null;
            Base\Str::typecastNotNull($newPassword,$newPasswordConfirm,$oldPassword,$oldPasswordHash);

            $validate = Base\Crypt::passwordValidate($newPassword,$newPasswordConfirm,$oldPassword,$oldPasswordHash,$security,$hashOption);

            if(!empty($validate))
            {
                $rule = $this->rulePattern(true);
                $neg = 'changePassword'.ucfirst($validate);
                static::catchable(['log'=>false,'errorLog'=>false],$neg,$rule);
            }

            else
            $return = Base\Crypt::passwordHash($newPassword,$hashOption);
        }

        elseif(is_string($value) && strlen($value))
        $return = $value;

        if(empty($return))
        $return = (!empty($cell))? $cell->value():null;

        return $return;
    }


    // onCommitted
    // lors d'un changement de mot de passe réussi que ce soit sur insertion ou mise à jour
    // utilisé pour ajouter de la communication et un log
    public function onCommitted(Orm\Cell $cell,bool $insert=false,array $option)
    {
        $pos = 'changePassword/success';

        if(!empty($option['com']) && $option['com'] === true && $insert === false)
        $cell->com($pos,'pos');

        if(!empty($option['log']) && $option['log'] === true)
        {
            $log = static::$config['log'];

            if(!empty($log))
            $log::logOnCloseDown('changePassword',['key'=>'changePassword','bool'=>true,'pos'=>$pos,'neg'=>null]);
        }

        return $this;
    }


    // inputs
    // retourne les inputs à utiliser pour le formulaire
    public function inputs(?array $attr=null,bool $required=false):array
    {
        $return = [];
        $lang = $this->db()->lang();
        $name = $this->name().'[]';
        $pattern = $this->rulePattern();

        foreach (['newPassword','newPasswordConfirm'] as $value)
        {
            $placeholder = $lang->text('changePassword/'.$value);
            $id = $attr['id'] ?? null;
            $array = ['name'=>$name,'id'=>$id,'placeholder'=>$placeholder,'data-required'=>$required,'data-pattern'=>$pattern];
            $return[$value] = $array;

            if(is_array($attr) && array_key_exists('id',$attr))
            unset($attr['id']);
        }

        return $return;
    }


    // formComplex
    // génère le formulaire complex pour password
    public function formComplex($value=true,?array $attr=null,?array $option=null):string
    {
        $return = '';
        $tag = $this->complexTag($attr);

        if(Base\Html::isFormTag($tag))
        {
            $value = $this->value($value);
            $required = (empty($value))? true:false;
            $inputs = $this->inputs($attr,$required);

            foreach ($inputs as $attr)
            {
                $return .= Base\Html::inputPassword(null,$attr);
            }
        }

        else
        $return = $this->formComplexNothing();

        return $return;
    }


    // getSecurity
    // retourne le niveau de sécurité du mot de passe
    public function getSecurity():?string
    {
        return $this->attr('security');
    }
}

// config
UserPassword::__config();
?>
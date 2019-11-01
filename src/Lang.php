<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Base;
use Quid\Main;
use Quid\Orm;

// lang
// extended class for a collection object containing language texts and translations
class Lang extends Main\Lang
{
    // trait
    use _bootAccess;


    // config
    public static $config = [
        'path'=>[ // chemin pour des types de texte précis liés à des méthodes
            'bool'=>'relation/bool',
            'direction'=>'direction',
            'bootLabel'=>'label',
            'bootDescription'=>'description',
            'typeLabel'=>'relation/contextType',
            'envLabel'=>'relation/contextEnv',
            'langLabel'=>'relation/lang',
            'dbLabel'=>'db/label',
            'dbDescription'=>'db/description',
            'tableLabel'=>'table/label',
            'tableDescription'=>'table/description',
            'colLabel'=>'col/label/*',
            'colTableLabel'=>'col/label',
            'colDescription'=>'col/description/*',
            'colTableDescription'=>'col/description',
            'rowLabel'=>'row/label',
            'rowDescription'=>'row/description',
            'panelLabel'=>'panel/label',
            'panelDescription'=>'panel/description',
            'roleLabel'=>'role/label',
            'roleDescription'=>'role/description',
            'routeLabel'=>'route/label',
            'routeDescription'=>'route/description',
            'relation'=>'relation',
            'compare'=>'compare',
            'validate'=>'validate',
            'required'=>'required',
            'editable'=>'editable',
            'unique'=>'unique']
    ];


    // onChange
    // ajout le shortcut dans orm/syntax
    // méthode protégé
    protected function onChange():void
    {
        parent::onChange();

        if($this->inInst())
        Orm\Syntax::setShortcut('lang',$this->currentLang());

        return;
    }


    // existsRelation
    // retourne vrai si un élément de relation existe et est texte
    public function existsRelation($value=null,?string $lang=null):bool
    {
        $return = $this->existsText($this->getPath('relation',$value),$lang);

        if($return === false)
        $return = $this->existsText($value,$lang);

        return $return;
    }


    // existsCom
    // retourne vrai si un élément de com existe pour le type et la valeur spécifié
    public function existsCom(string $type,$path,?string $lang=null):bool
    {
        return $this->existsText($this->getPath('com',[$type,$path]),$lang);
    }


    // direction
    // retourne le texte pour une direction, asc ou desc
    public function direction(string $key,$lang=null,?array $option=null):?string
    {
        return $this->text($this->getPath('direction',strtolower($key),null,$lang,$option));
    }


    // bootLabel
    // retourne le label du boot courant
    public function bootLabel(?string $lang=null,?array $option=null):?string
    {
        return $this->text($this->getPath('bootLabel'),null,$lang,$option);
    }


    // bootDescription
    // retourne la description du boot courant
    public function bootDescription(?string $lang=null,?array $option=null):?string
    {
        return $this->safe($this->getPath('bootDescription'),null,$lang,$option);
    }


    // typeLabel
    // retourne le label du type de context
    public function typeLabel(string $type,?string $lang=null,?array $option=null):?string
    {
        return $this->text($this->getPath('typeLabel',$type),null,$lang,$option);
    }


    // envLabel
    // retourne le label de l'env de context
    public function envLabel(string $env,?string $lang=null,?array $option=null):?string
    {
        return $this->text($this->getPath('envLabel',$env),null,$lang,$option);
    }


    // langLabel
    // retourne le label d'une langue
    public function langLabel(string $value,?string $lang=null,?array $option=null):?string
    {
        return $this->text($this->getPath('langLabel',$value),null,$lang,$option);
    }


    // dbLabel
    // retourne le label d'une base de donnée
    // si la db n'existe pas, utilise def
    public function dbLabel(string $tables,?string $lang=null,?array $option=null):?string
    {
        return $this->def($this->getPath('dbLabel',$tables),null,$lang,$option);
    }


    // dbDescription
    // retourne la description d'une base de donnée
    // par défaut, la méthode error n'est pas lancé et retournera null si aucune description
    public function dbDescription(string $tables,?array $replace=null,?string $lang=null,?array $option=null):?string
    {
        return $this->safe($this->getPath('dbDescription',$tables),$replace,$lang,$option);
    }


    // tableLabel
    // retourne le label d'une table
    // si la table n'existe pas, utilise def
    public function tableLabel(string $table,?string $lang=null,?array $option=null):?string
    {
        return $this->def($this->getPath('tableLabel',$table),null,$lang,$option);
    }


    // tableDescription
    // retourne la description d'une table
    // par défaut, la méthode error n'est plas lancé et retournera null si aucune description
    public function tableDescription(string $table,?array $replace=null,?string $lang=null,?array $option=null):?string
    {
        return $this->safe($this->getPath('tableDescription',$table),$replace,$lang,$option);
    }


    // colLabel
    // retourne le label d'une colonne, la string de la table est facultative
    // s'il y a table recherche dans col/label/table/*, ensuite dans col/label/*/*
    // s'il y a une table et toujours introuvable, regarde si la colonne a un nom de relation, si oui retourne le nom de la table
    // si toujours introuvable, utilise def
    public function colLabel(string $col,?string $table=null,?string $lang=null,?array $option=null):?string
    {
        $colLabel = $this->getPath('colLabel',$col);

        if(is_string($table))
        {
            $return = $this->safe($this->getPath('colTableLabel',[$table,$col]),null,$lang,$option);
            if(empty($return))
            {
                $return = $this->safe($colLabel,null,$lang,$option);

                if(empty($return))
                {
                    $table = Orm\ColSchema::table($col);

                    if(!empty($table))
                    $return = $this->tableLabel($table,$lang,$option);
                }
            }
        }

        if(empty($return))
        $return = $this->def($colLabel,null,$lang,$option);

        return $return;
    }


    // colDescription
    // retourne la description d'une colonne
    // utilise alt pour faire une recherche alternative dans col/description/* si introuvable sous le nom de la table, ou si pas de table donné
    // par défaut, la méthode error n'est plas lancé et la méthode retournera null si aucune description
    public function colDescription(string $col,?string $table=null,?array $replace=null,?string $lang=null,?array $option=null):?string
    {
        $colDescription = $this->getPath('colDescription',$col);

        if(is_string($table))
        $return = $this->alt($this->getPath('colTableDescription',[$table,$col]),$colDescription,$replace,$lang,Base\Arr::plus(['error'=>false],$option));
        else
        $return = $this->safe($colDescription,null,$lang,$option);

        return $return;
    }


    // rowLabel
    // retourne le label d'une row
    // le label de la table sera aussi cherché
    // une erreur sera envoyé si le texte et le texte alternatif n'existe pas
    public function rowLabel(int $primary,string $table,?string $lang=null,?array $option=null):?string
    {
        $return = null;
        $tableLabel = $this->tableLabel($table,$lang);
        $replace = ['primary'=>$primary,'table'=>$tableLabel];
        $return = $this->alt($this->getPath('rowLabel',$table),$this->getPath('rowLabel','*'),$replace,$lang,$option);

        return $return;
    }


    // rowDescription
    // retourne la description d'une row
    // le label de la table sera aussi cherché
    // une erreur sera envoyé si le texte et le texte alternatif n'existe pas
    public function rowDescription(int $primary,string $table,?array $replace=null,?string $lang=null,?array $option=null):?string
    {
        $return = null;
        $tableLabel = $this->tableLabel($table,$lang);
        $replace['primary'] = $primary;
        $replace['table'] = $tableLabel;
        $return = $this->alt($this->getPath('rowDescription',$table),$this->getPath('rowDescription','*'),$replace,$lang,Base\Arr::plus(['error'=>false],$option));

        return $return;
    }


    // panelLabel
    // retourne le label d'un panel
    // si le panel n'existe pas, utilise def
    public function panelLabel(string $panel,?string $lang=null,?array $option=null):?string
    {
        return $this->def($this->getPath('panelLabel',$panel),null,$lang,$option);
    }


    // panelDescription
    // retourne la description d'un panel
    // par défaut, la méthode error n'est pas lancé et retournera null si aucune description
    public function panelDescription(string $panel,?array $replace=null,?string $lang=null,?array $option=null):?string
    {
        return $this->safe($this->getPath('panelDescription',$panel),$replace,$lang,$option);
    }


    // roleLabel
    // retourne le label d'un role
    // une erreur sera envoyé si le role n'existe pas
    public function roleLabel(int $role,?string $lang=null,?array $option=null):?string
    {
        return $this->text($this->getPath('roleLabel',$role),null,$lang,$option);
    }


    // roleDescription
    // retourne la description d'un role
    // par défaut, la méthode error n'est pas lancé et retournera null si aucune description
    public function roleDescription(int $role,?array $replace=null,?string $lang=null,?array $option=null):?string
    {
        return $this->safe($this->getPath('roleDescription',$role),$replace,$lang,$option);
    }


    // routeLabel
    // retourne le label d'une route
    // si la route n'existe pas, utilise def
    public function routeLabel(string $route,?string $lang=null,?array $option=null):?string
    {
        return $this->def($this->getPath('routeLabel',$route),null,$lang,$option);
    }


    // routeDescription
    // retourne la description d'une route
    // par défaut, la méthode error n'est pas lancé et retournera null si aucune description
    public function routeDescription(string $route,?array $replace=null,?string $lang=null,?array $option=null):?string
    {
        return $this->safe($this->getPath('routeDescription',$route),$replace,$lang,$option);
    }


    // bool
    // retourne le texte pour un booléean, true ou false, 0 ou 1
    public function bool($key,$lang=null,?array $option=null):?string
    {
        return $this->text($this->getPath('bool',(is_scalar($key))? (int) $key:$key),null,$lang,$option);
    }


    // relation
    // retourne un texte ou un tableau de texte de relation
    // utilise take, donc pas d'option
    // si retour est null, fait un take sans le path relation
    // envoie une exception si le retour est toujours null
    // le tableau des relations est maintenant sort par valeur de clé
    // possible de sort le résultat, par défaut true
    public function relation($value=null,?string $lang=null,bool $sort=false)
    {
        $return = $this->take($this->getPath('relation',$value),$lang);

        if($return === null)
        {
            $return = $this->take($value,$lang);

            if($return === null)
            static::throw('notFound',$value);
        }

        if($sort === true && is_array($return) && !empty($return))
        ksort($return);

        return $return;
    }


    // validate
    // si value est null, retourne tout le tableau de contenu validate dans la langue
    // compatible avec base/lang
    // sinon retourne un texte d'erreur de validation
    // utilise def, donc aucune erreur envoyé si inexistant
    public function validate(?array $value=null,?string $lang=null,?array $option=null)
    {
        $return = null;
        $option = Base\Arr::plus(['path'=>null,'same'=>true],$option);

        if($value === null)
        $return = $this->pathAlternateTake('validate',$lang,$option['path']);

        elseif(is_array($value) && count($value) === 1)
        {
            $k = key($value);
            $v = current($value);
            $replace = null;
            $plural = null;

            if($v instanceof \Closure)
            $v = $v('lang');

            if(is_array($v))
            $v = implode(', ',$v);

            if(is_numeric($k))
            $path = $this->pathAlternateValue('validate',$v,true,$option['path']);

            else
            {
                $path = $this->pathAlternateValue('validate',$k,true,$option['path']);
                $replace = ['%'=>$v];

                if(is_int($v) || is_array($v))
                $plural = $v;
            }

            if(empty($plural))
            $return = $this->same($path,$replace,$lang,$option);
            else
            $return = $this->plural($plural,$path,$replace,['s'=>'s'],$lang,$option);
        }

        else
        static::throw('invalidValue','arrayNeedsToHaveOneKey');

        return $return;
    }


    // validates
    // retourne plusieurs textes d'erreur de validation
    // utilise def, donc aucune erreur envoyé si inexistant
    // retourne un tableau
    public function validates(array $values,?string $lang=null,?array $option=null):array
    {
        $return = [];

        foreach ($values as $key => $value)
        {
            if(is_numeric($key) && is_array($value) && count($value) === 1)
            {
                $key = key($value);
                $value = current($value);
            }

            $return[] = $this->validate([$key=>$value],$lang,$option);
        }

        return $return;
    }


    // compare
    // si value est null, retourne tout le tableau de contenu compare dans la langue
    // sinon retourne un texte d'erreur de validation
    // compatible avec base/lang
    // utilise def, donc aucune erreur envoyé si inexistant
    public function compare(?array $value=null,?string $lang=null,?array $option=null)
    {
        $return = null;
        $option = Base\Arr::plus(['path'=>null],$option);

        if($value === null)
        $return = $this->pathAlternateTake('compare',$lang,$option['path']);

        elseif(is_array($value) && count($value) === 1)
        {
            $symbol = key($value);
            $v = current($value);

            if(is_string($symbol) && is_string($v))
            {
                $path = $this->pathAlternateValue('compare',$symbol,true,$option['path']);
                $replace = ['%'=>$v];
                $return = $this->def($path,$replace,$lang,$option);
            }
        }

        return $return;
    }


    // compares
    // retourne plusieurs textes d'erreur de comparaison
    // utilise def, donc aucune erreur envoyé si inexistant
    // retourne un tableau
    public function compares(array $values,?string $lang=null,?array $option=null):array
    {
        $return = [];

        foreach ($values as $key => $value)
        {
            $return[] = $this->compare([$key=>$value],$lang,$option);
        }

        return $return;
    }


    // required
    // génère le message d'erreur pour champ requis
    // compatible avec base/lang
    public function required($value=null,?string $lang=null,?array $option=null)
    {
        $return = null;
        $option = Base\Arr::plus(['path'=>null],$option);

        if($value === null)
        $return = $this->pathAlternateTake('required',$lang,$option['path']);

        else
        {
            $path = $this->pathAlternateValue('required','common',false,$option['path']);
            $return = $this->text($path,null,$lang,$option);
        }

        return $return;
    }


    // unique
    // génère le message d'erreur pour champ devant être unique
    // compatible avec base/lang
    public function unique($value=null,?string $lang=null,?array $option=null)
    {
        $return = null;
        $option = Base\Arr::plus(['path'=>null],$option);

        if($value === null)
        $return = $this->pathAlternateTake('unique',$lang,$option['path']);

        else
        {
            $path = $this->pathAlternateValue('unique','common',false,$option['path']);
            $replace = ['%'=>''];

            if(is_array($value))
            {
                foreach ($value as $k => $v)
                {
                    if(is_scalar($v))
                    {
                        if(is_numeric($v))
                        $value[$k] = "#$v";
                    }

                    else
                    unset($value[$k]);
                }

                $value = implode(', ',$value);
            }

            if(is_scalar($value) && !is_bool($value))
            {
                $text = (is_numeric($value))? " (#$value)":" ($value)";
                $replace = ['%'=>$text];
            }

            $return = $this->text($path,$replace,$lang,$option);
        }

        return $return;
    }


    // editable
    // génère le message d'erreur pour champ editable
    // compatible avec base/lang
    public function editable($value=null,?string $lang=null,?array $option=null)
    {
        $return = null;
        $option = Base\Arr::plus(['path'=>null],$option);

        if($value === null)
        $return = $this->pathAlternateTake('editable',$lang,$option['path']);

        else
        {
            $path = $this->pathAlternateValue('editable','common',false,$option['path']);
            $return = $this->text($path,null,$lang,$option);
        }

        return $return;
    }


    // pathAlternate
    // méthode utilisé par les méthodes de validation pour lang
    // permet d'aller vérifier si un chemin alternatif existe pour validate, compare, required ou unique avec sans valeur
    public function pathAlternate(string $type,$alternate=null)
    {
        $return = null;

        if(!empty($type))
        {
            $base = $this->getPath($type);

            if(!empty($alternate))
            {
                $exists = Base\Arr::append($base,$alternate);

                if($this->exists($exists))
                $return = $exists;
            }

            if(empty($return))
            $return = $base;
        }

        return $return;
    }


    // pathAlternateTake
    // utilise pathAlternate et ensuite take
    public function pathAlternateTake(string $type,?string $lang=null,$alternate=null)
    {
        $return = null;
        $path = $this->pathAlternate($type,$alternate);
        $return = $this->take($path,$lang);

        return $return;
    }


    // pathAlternateValue
    // méthode utilisé par les méthodes de validation pour lang
    // permet d'aller vérifier si un chemin alternatif existe pour validate, compare, required ou unique avec une valeur
    public function pathAlternateValue(string $type,$value,bool $includeValue=true,$alternate=null)
    {
        $return = null;

        if(!empty($value) && !empty($type))
        {
            $base = $this->getPath($type);

            if(!empty($alternate))
            {
                $exists = Base\Arr::append($base,$alternate);

                if($includeValue === true)
                $exists = Base\Arr::append($exists,$value);

                if($this->exists($exists))
                $return = $exists;
            }

            if(empty($return))
            {
                $exists = Base\Arr::append($base,$value);

                if($this->exists($exists))
                $return = $exists;

                else
                $return = $value;
            }
        }

        return $return;
    }
}

// init
Lang::__init();
?>
<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Route;
use Quid\Core;
use Quid\Orm;

// _generalSegment
// trait that provides some methods for a general navigation page
trait _generalSegment
{
    // trait
    use _search;


    // hasPermission
    // méthode utilisé pour certaines requêtes de permissions
    protected function hasPermission(string ...$types):bool
    {
        return $this->table()->hasPermission(...$types);
    }


    // generalSegments
    // retourne les segments à utiliser pour la création de l'objet sql
    abstract protected function generalSegments():array;


    // sql
    // crée et conserve l'objet sql à partir des segments fournis par generalSegments
    // les segments dont la table n'a pas la permission sont effacés
    public function sql():Orm\Sql
    {
        return $this->cache(__METHOD__,function() {
            $return = null;
            $table = $this->table();
            $array = $this->generalSegments();

            if(!array_key_exists('where',$array))
            {
                $where = $table->where();
                if(!empty($where))
                $array['where'] = $where;
            }

            if(array_key_exists('limit',$array) && !is_int($array['limit']))
            $array['limit'] = $table->limit();

            $array['search'] = $this->getSearchValue();

            foreach ($array as $key => $value)
            {
                if(!$this->hasPermission($key))
                unset($array[$key]);
            }

            $return = $table->sql($array);

            return $return;
        });
    }


    // rows
    // retourne les rows de la route
    public function rows():Core\Rows
    {
        return $this->sql()->triggerRows();
    }


    // rowsVisible
    // retourne les rows visible de la route
    public function rowsVisible():Core\Rows
    {
        return $this->rows()->filter(['isVisible'=>true]);
    }


    // isSearchValueValid
    // retourne vrai si la valeur de recherche est valide
    protected function isSearchValueValid(string $value):bool
    {
        return ($this->table()->isSearchTermValid($value))? true:false;
    }
}
?>
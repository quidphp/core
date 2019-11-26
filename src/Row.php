<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Core;
use Quid\Orm;
use Quid\Routing;

// row
// extended class to represent an existing row within a table
class Row extends Orm\Row
{
    // trait
    use _accessAlias;
    use Routing\_attrRoute;


    // config
    public static $config = [];


    // isOwner
    // retourne vrai si l'utilisateur donné en argument est un des propriétaires de la ligne
    // si null prend l'utilisateur de la session courante
    final public function isOwner(?Row\User $user=null):bool
    {
        $return = false;
        $owner = $this->cellsOwner();

        if(empty($user))
        $user = static::session()->user();

        foreach ($owner as $cell)
        {
            if($cell->value() === $user->primary())
            {
                $return = true;
                break;
            }
        }

        return $return;
    }


    // inAllSegment
    // retourne vrai si la route doit être ajouté allSegment de route
    // en lien avec le sitemap
    public function inAllSegment():bool
    {
        return false;
    }


    // tableFromFqcn
    // retourne l'objet table à partir du fqcn de la classe
    // envoie une erreur si la table n'existe pas
    final public static function tableFromFqcn():Table
    {
        $return = (static::class !== self::class)? static::boot()->db()->table(static::class):null;

        if(!$return instanceof Table)
        static::throw();

        return $return;
    }


    // row
    // permet de retourner un objet row de la table
    final public static function row($row):?self
    {
        return static::tableFromFqcn()->row($row);
    }


    // rows
    // permet de retourner l'objet rows de la table
    final public static function rows(...$values):Rows
    {
        return static::tableFromFqcn()->rows(...$values);
    }


    // rowsVisible
    // permet de retourner l'objet rows de la table, mais l'objet contient seulement les lignes visibles
    final public static function rowsVisible(...$values):Rows
    {
        return static::tableFromFqcn()->rowsVisible(...$values);
    }


    // rowsVisibleOrder
    // permet de retourner l'objet rows de la table, mais l'objet contient seulement les lignes visibles et dans l'ordre par défaut de la table
    final public static function rowsVisibleOrder(...$values):Rows
    {
        return static::tableFromFqcn()->rowsVisibleOrder(...$values);
    }


    // select
    // permet de faire une requête select sur la table de la classe via méthode static
    final public static function select(...$values):?self
    {
        return static::tableFromFqcn()->select(...$values);
    }


    // selects
    // permet de faire une requête selects sur la table de la classe via méthode static
    final public static function selects(...$values):Rows
    {
        return static::tableFromFqcn()->selects(...$values);
    }


    // grab
    // permet de faire une requête selects (grab) sur la table de la classe via méthode static
    final public static function grab($where=null,$limit=null,bool $visible=false):Rows
    {
        return static::tableFromFqcn()->grab($where,$limit,$visible);
    }


    // grabVisible
    // permet de faire une requête select (grabVisible) sur la table de la classe via méthode static
    // seuls les rows qui passent la méthode isVisible sont retournés
    final public static function grabVisible($where=true,$limit=null):Rows
    {
        return static::tableFromFqcn()->grabVisible($where,$limit);
    }


    // insert
    // permet d'insérer une ligne dans la table à partir du fqcn
    final public static function insert(array $set=[],?array $option=null)
    {
        return static::tableFromFqcn()->insert($set,$option);
    }
}
?>
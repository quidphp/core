<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Main;
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
    protected static array $config = [];


    // onCommittedOrDeleted
    // au changement d'une row, vide la cache si la row l'exige
    protected function onCommittedOrDeleted(array $option)
    {
        $cacheEmpty = $this->getAttr('cacheEmpty');
        if($cacheEmpty === true)
        Row\CacheRoute::clearAll();

        return;
    }


    // isOwner
    // retourne vrai si l'utilisateur donné en argument est un des propriétaires de la ligne
    // si null prend l'utilisateur de la session courante
    final public function isOwner(?Row\User $user=null):bool
    {
        $owner = $this->cellsOwner();
        $user ??= static::session()->user();
        return $owner->some(fn($cell) => $cell->value() === $user->primary());
    }


    // cellsImageFile
    // tente de retourner la première image disponible pour représenter la row
    final public function cellsImageFile($version=null,?string $type=null):?Main\File
    {
        $return = null;

        foreach ($this->cells() as $cell)
        {
            if($cell->hasImage() && $cell->isNotEmpty())
            {
                $file = $cell->getFirstImage($version,$type);

                if(!empty($file))
                {
                    $return = $file;
                    break;
                }
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
        $value = (static::class !== self::class)? static::boot()->db()->table(static::class):null;
        return static::typecheck($value,Table::class);
    }


    // safeTableFromFqcn
    // comme tableFromFqcn, mais la méthode ne peut pas déclencher d'erreur
    // par exemple n'envoie pas d'erreur si la table n'existe pas ou si boot n'est pas prêt
    final public static function safeTableFromFqcn():?Table
    {
        $return = null;
        $boot = static::bootReady();

        if(!empty($boot) && $boot->hasDb() && $boot->hasSession())
        {
            $db = $boot->db();

            if(static::class !== self::class && $db->hasTable(static::class))
            $return = $db->table(static::class);
        }

        return $return;
    }


    // row
    // permet de retourner un objet row de la table
    final public static function row($row):?self
    {
        return static::tableFromFqcn()->row($row);
    }


    // rowVisible
    // permet de retourner un objet row visible de la table
    final public static function rowVisible($row):?self
    {
        return static::tableFromFqcn()->rowVisible($row);
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


    // safeInsert
    // permet d'insérer une ligne dans la table à partir du fqcn
    // les permissions est validé, les extras de la db sont mis à off
    final public static function safeInsert(array $set=[],?array $option=null)
    {
        $table = static::safeTableFromFqcn();
        return (!empty($table))? $table->safeInsert($set,$option):null;
    }
}
?>
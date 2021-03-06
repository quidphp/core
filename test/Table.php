<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\Base;
use Quid\Core;
use Quid\Test\Suite;

// table
// class for testing Quid\Core\Table
class Table extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $boot = Core\Boot::inst();
        $db = $boot->db();
        $table = 'ormTable';
        assert($db->truncate($table) instanceof \PDOStatement);
        assert($db->inserts($table,['id','active','name_en','dateAdd','userAdd','dateModify','userModify','name_fr','email','date'],[1,1,'james',10,11,12,13,'james_fr','james@james.com',123312213],[2,2,'james2',20,21,22,23,'james_fr','james@james.com',123312213]) === [1,2]);
        $tb = $db[$table];
        $nobody = $boot->roles()->nobody();
        $admin = $boot->roles()->get(80);

        // safeInsert

        // tableFromFqcn
        assert($tb::tableFromFqcn() === $tb);

        // route
        assert($tb->routeAttr('test') === null);

        // rowLog
        $rowLog = Core\Row\Log::class;
        $row = $rowLog::log('login',['save'=>'that']);
        assert($rowLog::logCloseDown('login',['save'=>'queue']) === null);
        assert($rowLog::logCloseDown('login',['save'=>'queue2']) === null);
        assert($row instanceof Core\Row);
        assert($row['type']->get() === 1);
        assert(count($row['context']->get()) === 4);
        assert($row['request']->get() instanceof Core\Request);
        assert($row['json']->get() === ['save'=>'that']);
        assert(is_int($row['session_id']->get()));
        assert(is_int($row['userCommit']->get()));
        assert($row['userAdd']->relationRow() instanceof Core\Row\User);
        assert(is_string($row['dateAdd']->get()));
        assert($row['userModify']->get() === null);
        assert($row['dateModify']->get() === null);
        $tble = $db->table($rowLog);
        assert($row->isLinked());
        $rowLogEmail = Core\Row\LogEmail::class;
        assert($rowLogEmail::log(false,['what'=>'ok'])['status']->value() === 0);
        assert($rowLogEmail::log(true,['what'=>'ok'])['status']->value() === 1);

        // orm
        assert($tb->rowsClass() === Core\Rows::class);
        assert($db->classe()->default('row') === Core\Row::class);
        assert(is_a($tb->rowClass(),Core\Row::class,true));
        assert($tb->classFqcn() === Suite\Table\OrmTable::class);
        assert($tb::getOverloadKeyPrepend() === 'Table');
        assert(Core\Table::getOverloadKeyPrepend() === null);

        // permission
        assert(count($tb->getPermission($nobody)) > 10);
        assert($tb->getPermission($admin) !== $tb->getPermission($nobody));
        assert($tb->roleHasPermission('insert',$admin));
        assert(!$tb->roleHasPermission('insert',$nobody));
        assert($tb->hasPermission('insert','update'));
        assert($tb->checkPermission('insert','update') === $tb);

        // cleanup
        assert($db->truncate($table) instanceof \PDOStatement);
        assert($db->truncate($rowLogEmail::tableFromFqcn()) instanceof \PDOStatement);

        return true;
    }
}
?>
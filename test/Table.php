<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\Base;
use Quid\Core;
use Quid\Suite;

// table
// class for testing Quid\Core\Table
class Table extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // prepare
        $db = Core\Boot::inst()->db();
        $table = 'ormTable';
        assert($db->truncate($table) instanceof \PDOStatement);
        assert($db->inserts($table,['id','active','name_en','dateAdd','userAdd','dateModify','userModify','name_fr','email','date'],[1,1,'james',10,11,12,13,'james_fr','james@james.com',123312213],[2,2,'james2',20,21,22,23,'james_fr','james@james.com',123312213]) === [1,2]);
        $tb = $db[$table];

        // tableFromFqcn
        assert($tb::tableFromFqcn() === $tb);

        // getOverloadKeyPrepend
        assert($tb::getOverloadKeyPrepend() === 'Table');
        assert(Core\Table::getOverloadKeyPrepend() === null);

        // route
        assert($tb->routeAttr('test') === null);

        // rowLog
        $rowLog = Core\Row\Log::class;
        assert($rowLog::newTable() instanceof Core\Table);
        $row = $rowLog::new('login',['save'=>'that']);
        assert($rowLog::logOnCloseDown('login',['save'=>'queue']) === null);
        assert($rowLog::logOnCloseDown('login',['save'=>'queue2']) === null);
        assert($row instanceof Core\Row);
        assert($row['type']->get() === 1);
        assert(count($row['context']->get()) === 3);
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
        assert($rowLogEmail::new(false,['what'=>'ok'])['status']->value() === 0);
        assert($rowLogEmail::new(true,['what'=>'ok'])['status']->value() === 1);
        $array = ['test'=>Base\Str::loremIpsum(30)];
        $sql = Core\Row\LogSql::log('insert',$array);
        assert(!$sql['json']->isInvalidValue());
        $array = ['test'=>Base\Str::loremIpsum(3000)];
        $tooLong = Core\Row\LogSql::log('insert',$array);
        assert($tooLong['json']->isInvalidValue());

        // orm
        assert($tb->rowsClass() === Core\Rows::class);
        assert($db->classe()->default('row') === Core\Row::class);
        assert(is_a($tb->rowClass(),Core\Row::class,true));
        assert($tb->classFqcn() === Suite\Table\OrmTable::class);

        // permission
        assert(Base\Arrs::is($tb->permissionAll()));
        assert(count($tb->permissionRole(Core\Role\Nobody::class)) > 10);
        assert($tb->permissionRole(new Core\Role\Nobody()) === $tb->permissionRole(Core\Role\Nobody::class));
        assert($tb->permissionRole(Core\Role\Admin::class) !== $tb->permissionRole(Core\Role\Nobody::class));
        assert($tb->permissionCan('insert',Core\Role\Admin::class));
        assert(!$tb->permissionCan('insert',Core\Role\Nobody::class));
        assert($tb->hasPermission('insert','update'));
        assert($tb->checkPermission('insert','update') === $tb);

        // cleanup
        assert($db->truncate($table) instanceof \PDOStatement);
        assert($db->truncate($rowLogEmail::tableFromFqcn()) instanceof \PDOStatement);

        return true;
    }
}
?>
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
use Quid\Main;
use Quid\Orm;

// cell
// class for testing Quid\Core\Cell
class Cell extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $boot = Core\Boot::inst();
        $db = $boot->db();
        $admin = $boot->roles()->get(80);
        $table = 'ormCell';
        assert($db->truncate($table) instanceof \PDOStatement);
        assert($db->inserts($table,['id','date','name','dateAdd','userAdd','dateModify','userModify','integer','enum','set','user_ids'],[1,time(),'james',10,2,12,13,12,5,'2,3',[2,1]],[2,time(),'james2',10,11,12,13,12,5,'2,4','2,3']) === [1,2]);
        $tb = $db[$table];
        $row = $tb[1];
        $_file_ = Base\Finder::normalize('[assertCommon]/class.php');
        $public = '[storagePublic]/storage/ormCell';
        $mediaJpg = '[assertMedia]/jpg.jpg';
        $image = Main\File::new($mediaJpg);
        assert(Base\Dir::reset($public));
        $primary = $row->cell('id');
        $integer = $row->cell('integer');
        $dateAdd = $row->cell('dateAdd');
        $date = $row->cell('date');
        $enum = $row->cell('enum');
        $set = $row->cell('set');
        $userAdd = $row->cell('userAdd');
        $userIds = $row->cell('user_ids');
        $media = $row->cell('media');
        $thumbnail = $row->cell('thumbnail');
        $medias = $row->cell('medias');
        $thumbnails = $row->cell('thumbnails');
        $float = $row->cell('float');
        $active = $row->cell('active');

        // hasImage

        // orm
        assert(is_array($dateAdd->getPermission($admin)));
        assert($primary instanceof Core\Cell\Primary);
        assert($integer instanceof Core\Cell\Integer);
        assert($dateAdd->set(1234235434) === $dateAdd);
        assert($dateAdd::getOverloadKeyPrepend() === 'Cell');

        // active
        assert($active->value() === 2);
        $active->set(true);
        assert($active->value() === 1);
        $active->set(12);
        assert($active->value() === 12);
        $active->set(null);
        assert($active->value() === null);
        $active->set(false); // détecte que la relation 0 n'existe pas
        assert($active->value() === null);

        // date
        assert($dateAdd instanceof Core\Cell\Date);
        assert($date instanceof Core\Cell\Date);
        assert($dateAdd->format('sql') === '2009-02-09 22:10:34');
        assert($dateAdd->pair('sql') === $dateAdd->format('sql'));
        assert($dateAdd->reset());
        assert($dateAdd->isBefore(time()));
        assert(!$dateAdd->isAfter(time()));

        // enum
        $enum->row()['user_id']->set(1);
        assert($enum->colRelation() instanceof Orm\ColRelation);
        assert($enum instanceof Core\Cell\Relation);
        assert($enum instanceof Core\Cell\Enum);
        assert($enum->value() === 5);
        assert($enum->get() === 5);
        assert($enum->relation() === 'bla');
        assert($enum->set(1) === $enum);
        assert($enum->relation() === null);
        assert($enum->set(2) === $enum);
        assert($enum->relation() === 'oken');
        assert($enum->row()->updateChanged() === 1);
        assert($enum->row()->updateChanged(['include'=>false]) === null);
        assert($enum->row()->updateChanged() === 0);
        assert($enum->unset() === $enum);
        assert($enum->get() === null);
        assert($enum->relation() === null);
        $enum->set(3);
        assert($enum->row()->updateChanged(['strict'=>true]) === 1);
        assert($userAdd->relation() === 'admin');
        assert($userIds->relationKeyValue() === [2=>'admin',1=>'nobody']);
        assert($userAdd->relationRow() instanceof Core\Row);
        assert($userAdd->isEnum());
        assert($userAdd->isRelationTable());
        assert($userAdd->relationTable() instanceof Core\Table);
        assert($db['session']->col('userAdd')->relation()->count() === $userIds->colRelation()->count());

        // files

        // floating
        assert($float instanceof Core\Cell\Num);
        assert($float->col() instanceof Core\Col\Floating);
        assert($float->set('1.20') === $float);
        assert($float->pair('$') === '$1.20');
        $float->set('1,30');
        assert($float->value() === 1.3);

        // integer
        assert($integer instanceof Core\Cell\Integer);
        assert(!$integer->isPrimary());
        assert(!$primary->col()->hasDefault());
        assert($integer->col()->hasDefault());
        assert($primary->value() === 1);
        assert($primary->col()->default() === 0);
        assert($integer->col()->default() === null);
        assert($integer->delete() === $integer);
        $integer->set(12);
        assert($integer->value() === 12);
        assert($integer->increment()->value() === 13);
        assert($integer->set(null)->increment()->value() === 1);
        assert($integer->set(13)->isEqual(13));
        assert($integer->decrement()->value() === 12);
        assert($integer->set(null)->increment()->value() === 1);
        assert($integer->set(true) === $integer);
        assert($integer->value() === 1);

        // media + medias
        assert($media instanceof Core\Cell\Media);
        assert(is_string($media->rootPath()));
        assert(is_string($media->tablePath()));
        assert($media->rootPath(false) === '[storagePublic]/storage');
        assert(is_string($media->cellPath()));
        assert(is_string($media->basePath()));
        assert($media->filePath() === null);
        assert($media->fileExists() === false);
        $tmp = Main\File::new(Base\File::prefix(null));
        $tmp2 = Main\File::new(Base\File::prefix(null));
        assert($media->set($tmp) === $media);
        assert($media->row()->updateChanged() === 1);
        assert(is_string($media->basename()));
        assert($media->file() instanceof Main\File\Txt);
        assert(!$media->file()->isMimeFamily('image'));
        assert($media->file()->size() === 0);
        assert($media->file()->extension() === 'txt');
        assert(Base\Mime::isEmpty($media->file()->mime()));
        assert($media->file()->mimeGroup(false) === null);
        assert($media->file()->mimeGroup() === 'txt');
        assert($media->file()->mimeFamily(false) === null);
        assert($media->file()->mimeFamily() === 'text');
        assert($media->file()->mimeFamilies(false) === null);
        assert($media->file()->mimeFamilies() === ['text']);

        // la création de deux fois les mêmes directoires ne fonctionnent pas sous windows
        if(!Base\Server::isWindows())
        {
            assert($media->set($tmp2) === $media);
            assert($media->row()->updateChanged() === 1);
        }

        assert($thumbnail->set($image) === $thumbnail);
        assert($thumbnail->row()->updateChanged() === 1);
        assert($thumbnail->pair() === $thumbnail);
        assert($thumbnail->pair(true) instanceof Main\File);
        assert($thumbnail->pair('large') instanceof Main\File);
        assert($medias instanceof Core\Cell\Medias);
        $files = Main\Files::newOverload();
        $files->sets([2=>$_file_,3=>$_file_]);
        assert($medias->set($files));
        assert($medias->basename(2) === 'class.php');
        assert($medias->cellPath(2) === '/ormCell/1/medias/2');
        assert($medias->cellPathBasename(2) === '/ormCell/1/medias/2/class.php');
        assert(!empty($medias->basePath(2)));
        assert(!empty($medias->filePath(2)));
        assert(!$medias->fileExists(2));
        assert($media->row()->updateChanged() === 1);
        assert($medias->checkFileExists(2) === $medias);
        $file = $medias->file(2);
        assert($file instanceof Core\File\Php);
        assert(!empty($file->pathToUri()));
        assert(!$medias->file(2)->isMimeFamily('image'));
        assert($medias->indexes()->isCount(2));
        assert($medias->all()->isCount(2));
        $files = Main\Files::newOverload();
        $files->sets([2=>$image,4=>$image]);
        assert($thumbnails->set($files) === $thumbnails);
        assert($thumbnails->row()->updateChanged() === 1);
        assert($thumbnails->indexes()->isCount(2));
        assert($thumbnails->version(2)->isCount(1));
        assert($thumbnails->all()->isCount(4));
        assert($thumbnail->versionExtension('large') === 'jpg');
        assert($thumbnails->versionExtension(0,'large') === 'jpg');
        assert($thumbnails->cellPath(false,null) === '/ormCell/1/thumbnails');
        assert($thumbnails->cellPath(null,false) === '/ormCell/1/thumbnails/0');
        assert($thumbnails->cellPath(false,false) === '/ormCell/1/thumbnails');
        assert($thumbnails->basePath() !== $thumbnails->basePath(false,false));
        assert($thumbnails->pair(true) instanceof Main\Files);
        assert($thumbnails->pair('large')->isNotEmpty());
        assert($thumbnails->pair(0,'large') === null);
        assert($thumbnails->pair(2,'large') instanceof Main\File);
        assert($thumbnails->pair() === $thumbnails);

        // primary
        assert($primary->isPrimary());

        // relation

        // set
        assert($set->colRelation() instanceof Orm\ColRelation);
        assert($set instanceof Core\Cell\Relation);
        assert($set instanceof Core\Cell\Set);
        assert($set->value() === '2,3');
        assert($set->get() === [2,3]);
        assert($set->relation() === [2=>'ok',3=>'well']);
        assert($set->set([3,2,6]) === $set);
        assert($set->relation() === [3=>'well',2=>'ok',6=>null]);
        assert($set->relationFound() === [3=>'well',2=>'ok']);
        assert($set->set('2,3') === $set);
        assert($set->relation() === [2=>'ok',3=>'well']);
        assert($set->set(null) === $set);
        assert($set->get() === null);
        assert($set->relation() === null);
        assert(!$userIds->isEnum());
        assert($userIds->isSet());
        assert($userIds->relation() === [2=>'admin',1=>'nobody']);
        assert($userIds->relationRows()->isCount(2));
        assert($userIds->isRelationTable());
        assert($userIds->relationTable() instanceof Core\Table);

        // userPasswordReset

        // cleanup
        Base\File::unlinks($tmp,$tmp2);
        Base\Dir::emptyAndUnlink($public);
        assert($db->truncate($table) instanceof \PDOStatement);

        return true;
    }
}
?>
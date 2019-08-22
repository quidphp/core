<?php
declare(strict_types=1);
namespace Quid\Core\Test;
use Quid\Core;
use Quid\Orm;
use Quid\Base;

// cell
class Cell extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare 
		$db = Core\Boot::inst()->db();
		$table = 'ormCell';
		$tb = $db[$table];
		assert($db->truncate($table) instanceof \PDOStatement);
		assert($db->inserts($table,array('id','date','name','dateAdd','userAdd','dateModify','userModify','integer','enum','set','user_ids'),array(1,time(),'james',10,2,12,13,12,5,"2,3",array(2,1)),array(2,time(),'james2',10,11,12,13,12,5,"2,4","2,3")) === array(1,2));
		$tb = $db[$table];
		$row = $tb[1];
		$_file_ = Base\Finder::shortcut("[assertCommon]/class.php");
		$public = "[storagePublic]/storage/ormCell";
		$mediaJpg = "[assertMedia]/jpg.jpg";
		$image = Core\File::new($mediaJpg);
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

		// getOverloadKeyPrepend
		assert($dateAdd::getOverloadKeyPrepend() === 'Cell');

		// orm
		assert($primary instanceof Core\Cell\Primary);
		assert($integer instanceof Core\Cell\Integer);
		assert($dateAdd->set(1234235434) === $dateAdd);

		// date
		assert($dateAdd instanceof Core\Cell\Date);
		assert($date instanceof Core\Cell\Date);
		assert($dateAdd->format('sql') === '2009-02-09 22:10:34');
		assert($dateAdd->pair('sql') === $dateAdd->format('sql'));
		assert(strlen($date->formComplex()) === 279);
		assert($dateAdd->formComplex() === "<div>February 9, 2009 22:10:34</div>");
		assert($dateAdd->reset());
		
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
		assert($enum->row()->updateChangedIncluded() === 1);
		assert($enum->row()->updateChanged() === null);
		assert($enum->row()->updateChangedIncluded() === 0);
		assert($enum->unset() === $enum);
		assert($enum->get() === null);
		assert($enum->relation() === null);
		$enum->set(3);
		assert($enum->row()->updateChangedIncluded(array('strict'=>true)) === 1);
		assert($userAdd->relation() === 'admin (#2)');
		assert($userIds->relationKeyValue() === array(2=>'admin (#2)',1=>'nobody (#1)'));
		assert($userAdd->relationRow() instanceof Core\Row);
		assert($userAdd->isEnum());
		assert($userAdd->isRelationTable());
		assert($userAdd->relationTable() instanceof Core\Table);
		assert($db['session']->col('userAdd')->relation()->count() === $userIds->colRelation()->count());

		// files
		
		// floating
		assert($float instanceof Core\Cell\Floating);
		assert($float->col() instanceof Core\Col\Floating);
		assert($float->set('1.20') === $float);
		assert($float->pair('$') === '$1.20');

		// integer
		assert($integer instanceof Core\Cell\Integer);
		assert(!$integer->isPrimary());
		assert($integer->isColKindInt());
		assert(!$primary->hasDefault());
		assert($integer->hasDefault());
		assert($primary->value() === 1);
		assert($primary->colDefault() === 0);
		assert($integer->colDefault() === null);
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

		// jsonArray
		
		// jsonArrayRelation
		
		// media + medias
		assert($media instanceof Core\Cell\Media);
		assert(is_string($media->rootPath()));
		assert(is_string($media->tablePath()));
		assert($media->rootPath(false) === '[storagePublic]/storage');
		assert(is_string($media->cellPath()));
		assert(is_string($media->basePath()));
		assert($media->filePath() === null);
		assert($media->fileExists() === false);
		$tmp = Core\File::new(Base\File::prefix(null));
		$tmp2 = Core\File::new(Base\File::prefix(null));
		assert($media->set($tmp) === $media);
		assert($media->row()->updateChangedIncluded() === 1);
		assert(is_string($media->basename()));
		assert($media->file() instanceof Core\File\Txt);
		assert(!$media->isImage());
		assert($media->file()->size() === 0);
		assert($media->file()->extension() === 'txt');
		assert($media->file()->mime() === 'inode/x-empty; charset=binary');
		assert($media->file()->mimeGroup(false) === null);
		assert($media->file()->mimeGroup() === 'txt');
		assert($media->file()->mimeFamily(false) === null);
		assert($media->file()->mimeFamily() === 'text');
		assert($media->file()->mimeFamilies(false) === null);
		assert($media->file()->mimeFamilies() === array('text'));
		assert($media->set($tmp2) === $media);
		assert($media->row()->updateChangedIncluded() === 1);
		assert($thumbnail->set($image) === $thumbnail);
		assert($thumbnail->row()->updateChangedIncluded() === 1);
		assert($medias instanceof Core\Cell\Medias);
		$files = Core\Files::newOverload();
		$files->sets(array(2=>$_file_,3=>$_file_));
		assert($medias->set($files));
		assert($medias->basename(2) === 'class.php');
		assert($medias->cellPath(2) === '/ormCell/1/medias/2');
		assert($medias->cellPathBasename(2) === '/ormCell/1/medias/2/class.php');
		assert(!empty($medias->basePath(2)));
		assert(!empty($medias->filePath(2)));
		assert(!$medias->fileExists(2));
		assert($media->row()->updateChangedIncluded() === 1);
		assert($medias->checkFileExists(2) === $medias);
		$file = $medias->file(2);
		assert($file instanceof Core\File\Php);
		assert(!empty($file->pathToUri()));
		assert(!$medias->isImage(2));
		assert($medias->indexes()->isCount(2));
		assert($medias->all()->isCount(2));
		$files = Core\Files::newOverload();
		$files->sets(array(2=>$image,4=>$image));
		assert($thumbnails->set($files) === $thumbnails);
		assert($thumbnails->row()->updateChangedIncluded() === 1);
		assert($thumbnails->indexes()->isCount(2));
		assert($thumbnails->version(2)->isCount(1));
		assert($thumbnails->all()->isCount(4));
		assert($thumbnail->versionExtension('large') === 'jpg');
		assert($thumbnails->versionExtension(0,'large') === 'jpg');
		assert($thumbnails->cellPath(false,null) === "/ormCell/1/thumbnails");
		assert($thumbnails->cellPath(null,false) === "/ormCell/1/thumbnails/0");
		assert($thumbnails->cellPath(false,false) === "/ormCell/1/thumbnails");
		assert($thumbnails->basePath() !== $thumbnails->basePath(false,false));

		// primary
		assert($primary->isPrimary());

		// relation

		// set
		assert($set->colRelation() instanceof Orm\ColRelation);
		assert($set instanceof Core\Cell\Relation);
		assert($set instanceof Core\Cell\Set);
		assert($set->value() === '2,3');
		assert($set->get() === array(2,3));
		assert($set->relation() === array(2=>'ok',3=>'well'));
		assert($set->set(array(3,2,6)) === $set);
		assert($set->relation() === array(3=>'well',2=>'ok',6=>null));
		assert($set->relationFound() === array(3=>'well',2=>'ok'));
		assert($set->set('2,3') === $set);
		assert($set->relation() === array(2=>'ok',3=>'well'));
		assert($set->set(null) === $set);
		assert($set->get() === null);
		assert($set->relation() === null);
		assert($userIds->isRelation());
		assert(!$userIds->isEnum());
		assert($userIds->isSet());
		assert($userIds->relation() === array(2=>'admin (#2)',1=>'nobody (#1)'));
		assert($userIds->relationRows()->isCount(2));
		assert($userIds->isRelationTable());
		assert($userIds->relationTable() instanceof Core\Table);

		// userPasswordReset

		// video

		// cleanup
		assert(Base\Dir::emptyAndUnlink($public));
		assert($db->truncate($table) instanceof \PDOStatement);
		
		return true;
	}
}
?>
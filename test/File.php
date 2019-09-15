<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// file
// class for testing Quid\Core\File
class File extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // prepare
        $sessionDirname = Core\File\Session::storageDirname();
        Base\Dir::set($sessionDirname);
        $mediaJpg = '[assertMedia]/jpg.jpg';
        $mediaJpgUri = Base\Uri::relative($mediaJpg);
        $mediaVector = '[assertMedia]/svg.svg';
        $mediaVectorUri = Base\Uri::relative($mediaVector);
        $mediaPdf = '[assertMedia]/pdf.pdf';
        $mediaPng = '[assertMedia]/png.png';
        $storage = '[assertCurrent]';
        Base\File::unlink($storage.'/newZip.zip');
        $audio = Core\File::newCreate($storage.'/create.mp3');
        $audio2 = Core\File::new($audio);
        $font = Core\File::new($storage.'/create.ttf',['create'=>true]);
        $video = Core\File::new($storage.'/create.mp4',['create'=>true]);
        $create = Core\File::new($storage.'/create.jpg',['create'=>true]);
        $csv = Core\File::new('[assertCommon]/csv.csv',['toUtf8'=>true]);
        $text = Core\File::newCreate($storage.'/index.php');
        $text->write("lorem ipsum lorem ipsum\nlorem ipsum lorem ipsum2\nlorem ipsum lorem ipsum3\nlorem ipsum lorem ipsum4");
        $raster = Core\File::new($mediaJpg);
        $rasterStorage = Core\File::new('[assertCommon]/png.png');
        $vector = Core\File::new($mediaVector);
        $vectorStorage = Core\File::new('[assertCommon]/svg.svg');
        $doc = Core\File::new($storage.'/document345.doc');
        $pdf = Core\File::new($mediaPdf);
        $calendar = Core\File::new($storage.'/ics.ics',['create'=>true]);
        $calendarTemp = Core\File\Calendar::new(true);
        $res = Base\Res::temp('csv','ok.csv');
        $tempCsv = Core\File\Csv::new($res);
        $core = Core\File::new(true);
        $text2 = Core\File::new(true,['mime'=>'txt']);
        $raster2 = Core\File::new(true,['mime'=>'jpg']);
        $pngMime = Core\File::new(true,['mime'=>'image/png']);
        $jpgBasename = Core\File::new(true,['basename'=>'test.jpg']);
        $csv2 = Core\File\Csv::new(true);
        $pngTemp = Core\File::new(Base\Res::temp('png','temp.png'));
        $png = Core\File::newCreate($mediaPng);
        $serialize = Core\File\Serialize::new($storage.'/serialize.txt',['create'=>true]);
        $dump = Core\File\Dump::new($storage.'/serialize.html',['create'=>true]);
        $html = Core\File::new(true,['mime'=>'html']);
        $json = Core\File::new($storage.'/json.json',['create'=>true]);
        $xml = Core\File::new($storage.'/xml.xml',['create'=>true]);
        $zip = Core\File::new('[assertCommon]/zip.zip');
        $newZip = Core\File::newCreate($storage.'/newZip.zip');
        $newXml = Core\File::new($storage."/xml2éèàsad l'article.xml",['create'=>true]);
        $file = new Core\File($storage.'/test.php',['create'=>true]);
        $imgMime = new Core\File($storage.'/test.jpg',['mime'=>'jpg','create'=>true]);
        $temp = new Core\File(true);

        // getOverloadKeyPrepend

        // main
        assert($file::getClass($storage.'/test.php') === Core\File\Php::class);
        assert($file::getClassFromGroup('pdf') === Core\File\Pdf::class);
        assert($file::getClassFromDirname($sessionDirname) === Core\File\Session::class);
        assert($file::getClassFromDirname($sessionDirname.'/new') === Core\File\Session::class);

        // temp
        assert($tempCsv instanceof Core\File\Csv);
        assert($tempCsv->isPhp());
        assert($tempCsv->isPhpTemp());
        assert($tempCsv->isPhpWritable());
        assert($tempCsv->kind() === 'phpTemp');
        assert($tempCsv->mode() === 'w+b');
        assert($tempCsv->mode(true) === 'w+');

        // binary
        assert($audio instanceof Core\File\Binary);

        // audio
        assert($audio instanceof Core\File\Audio);
        assert($audio->isResourceValid());
        assert($audio === $audio2);
        assert($audio::defaultExtension() === 'mp3');
        $audioTest = Core\File::newCreate($audio);
        assert($audioTest === $audio);
        $audioTest = Core\File::new($audio);
        assert($audioTest === $audio);

        // cache
        $new = Core\File\Cache::storage([1,2,3]);
        assert($new instanceof Core\File\Cache);
        $cache = Core\File\Cache::storageAll()->first();
        assert($cache instanceof Core\File\Cache);
        assert($new->read() === [1,2,3]);
        assert($new->unlink());

        // calendar
        assert($calendar instanceof Core\File\Calendar);
        assert($calendar instanceof Core\File\Text);
        assert($calendar::defaultMimeGroup() === 'calendar');
        assert($calendar->size() === 0);
        assert($calendar->unlink());
        assert($calendarTemp instanceof Core\File\Calendar);
        assert($calendarTemp->mime() === 'text/calendar');

        // css

        // csv
        assert($csv instanceof Core\File\Csv);
        assert($csv2 instanceof Core\File\Csv);
        assert(is_resource($csv->resource()));
        assert(is_string($csv->read()));
        assert(Base\Column::is($csv->lines()));
        assert($csv->line() === null);
        assert($csv->seek(0) === $csv);
        assert(count($csv->line()) === 12);
        assert($csv::defaultMimeGroup() === 'csv');
        $csv->seek(0);
        $i = 20;
        assert(count($csv->lineRef(true,true,$i)) === 12);

        // doc
        assert($doc instanceof Core\File\Doc);

        // dump
        assert($dump instanceof Core\File\Dump);
        $write = new Main\Map([2=>'ok','yes',4]);
        assert($dump->write($write) === $dump);
        assert(empty($dump->readOption()['callback']));
        assert(!empty($dump->writeOption()));
        assert(is_string($dump->read()));
        assert($dump->extension() === 'html');

        // email
        $email = Core\File\Email::newCreate($storage.'/email.json');
        $email->writeRaw('{
			"contentType": "txt",
			"subject": "OK",
			"body": "Lorem ipsum"
		}');
        assert(is_array($email->read()));
        assert($email->contentType() === 'txt');
        assert($email->subject() === 'OK');
        assert($email->body() === 'Lorem ipsum');
        assert($email[0] === '{');
        foreach ($email as $key => $value) {
            assert(is_int($key));
            assert(is_string($value));
        }
        assert(count($email) === 5);

        // error
        $error = new Core\Error();
        $new = Core\File\Error::log($error);
        $new2 = Core\File\Error::log($error2 = new Core\Error());
        $new3 = Core\File\Error::log($error3 = new Core\Error());
        $new4 = Core\File\Error::log($error4 = new Core\Error());
        $new5 = Core\File\Error::log($error5 = new Core\Error());
        assert($new3->extension() === 'html');
        assert(Core\File::new($new5->path()) instanceof Core\File\Error);
        assert(Core\File::new($new5->path()) !== $new5);
        assert(Core\File::new($new5->resource()) instanceof Core\File\Error);
        assert(Core\File\Error::isStorageDataValid($error));
        assert(!Core\File\Error::isStorageDataValid('lol'));
        assert(is_array(Core\File\Error::storageData($error)));
        assert(Base\Str::isStart($error->id(),Core\File\Error::storageFilename($error)));
        assert($new instanceof Core\File\Error);
        assert(Core\File\Error::logTrim() === 0);
        Base\Dir::empty(Core\File\Error::storageDirname());

        // font
        assert($font instanceof Core\File\Font);

        // html
        assert($html instanceof Core\File\Html);

        // imageRaster
        assert($pngMime instanceof Core\File\ImageRaster);
        assert($jpgBasename instanceof Core\File\ImageRaster);
        assert($create instanceof Core\File\ImageRaster);
        assert(is_resource($create->resource()));
        assert($create->unlink());
        assert($raster instanceof Core\File\Image);
        assert($raster instanceof Core\File\ImageRaster);
        assert($raster2 instanceof Core\File\ImageRaster);
        assert(!$raster->isEmpty());
        assert($raster2->isEmpty());
        assert($raster->isNotEmpty());
        assert($raster->isMimeGroup('imageRaster'));
        assert(count($raster->info()) === 18);
        assert(count($raster->stat()) === 26);
        assert($raster->size() > 0);
        assert($raster->mime() === 'image/jpeg; charset=binary');
        assert($raster->mimeGroup() === 'imageRaster');
        assert($raster->uri() === $raster->path());
        assert(!empty($raster->path()));
        assert($raster->basename() === 'jpg.jpg');
        assert($raster->filename() === 'jpg');
        assert($raster->extension() === 'jpg');
        assert($raster::defaultMimeGroup() === 'imageRaster');
        assert($png instanceof Core\File\Image);
        assert($png->mime() === 'image/png; charset=binary');
        assert($pngTemp instanceof Core\File\Image);
        assert($pngTemp->mime() === 'image/png');
        assert($pngTemp->write($png));
        assert(strlen(Base\Html::img($pngTemp)) > 2000);
        assert(Base\Html::a($raster) === "<a href='".$mediaJpgUri."'></a>");
        assert(Base\Html::aOpen($raster) === "<a href='".$mediaJpgUri."'>");
        assert($raster->safeBasename() === 'jpg.jpg');
        assert(strlen($rasterStorage->img()) > 2500);

        // imageVector
        assert($vector instanceof Core\File\Image);
        assert($vector instanceof Core\File\ImageVector);
        assert($vector->mimeGroup() === 'imageVector');
        assert($vector::defaultMimeGroup() === 'imageVector');
        assert($vector->img() === "<img alt='svg' src='".$mediaVectorUri."'/>");
        assert(strlen($vectorStorage->img()) === 425);

        // js

        // json
        assert($json instanceof Core\File\Json);
        $write = ['test'=>'ok',2,3];
        assert($json->write($write));
        assert($json->read() === $write);
        assert(is_string($json->readRaw()));
        assert($json->unlink());

        // log
        $write = new Main\Map([2=>'test',3,'ok']);
        assert(Core\File\Log::isStorageDataValid());
        assert(!empty(Core\File\Log::storageDirname()));
        assert(Core\File\Log::storageFilename() === Base\Response::id().'-0');
        assert(Base\File::isWritableOrCreatable(Core\File\Log::storagePath($write)));
        assert(Core\File\Log::storageData($write) === $write);
        assert(Core\File\Log::storageData($write,2) === [$write,2]);
        assert(($log = Core\File\Log::log($write)) instanceof Core\File\Log);
        assert(Core\File::new($log->resource()) instanceof Core\File\Log);
        assert(Core\File\Log::logTrim() === 0);
        assert($log->unlink());

        // pdf
        assert($pdf instanceof Core\File\Pdf);
        assert($pdf instanceof Core\File\Binary);
        assert($pdf::defaultMimeGroup() === 'pdf');

        // php
        assert($text instanceof Core\File\Php);

        // queue
        assert(Core\File\Queue::setUnqueueCallable(function() {
            assert($this instanceof Core\File\Queue);
            return 'test';
        }) === null);
        assert(is_int(Core\File\Queue::storageAll()->unlink()));
        $data = ['what'=>'ok'];
        $queue = Core\File\Queue::queue($data);
        assert($queue->extension() === null);
        $data = ['what2'=>'ok'];
        $queue2 = Core\File\Queue::queue($data);
        assert($queue instanceof Core\File\Queue);
        assert(Core\File\Queue::storageAll()->isNotEmpty());
        assert(Core\File\Queue::storageSort()->isNotEmpty());
        assert(Core\File\Queue::storageSkip(1)->isCount(1));
        assert(Core\File\Queue::getQueued()->isCount(2));
        assert(Core\File\Queue::storageAll()->first() instanceof Core\File\Queue);
        assert(Core\File\Queue::storageAll()->first()->read() === ['what'=>'ok']);
        assert(Core\File\Queue::triggerUnqueue(1) === ['test']);
        assert(Core\File\Queue::storageAll()->unlink() === 1);
        assert(Core\File\Queue::storageTrim(2) === 0);
        assert(Core\File\Queue::triggerUnqueue(1) === null);
        Base\Dir::empty(Core\File\Queue::storageDirname());

        // serialize
        $write = new Main\Map([2=>'ok','yes',4]);
        assert($serialize instanceof Core\File\Serialize);
        assert($serialize->extension() === 'txt');
        assert($serialize->write($write) === $serialize);
        assert(!empty($serialize->readOption()['callback']));
        assert(!empty($serialize->writeOption()['callback']));
        assert($serialize->read() instanceof Main\Map);
        assert(is_string($serialize->readRaw()));
        assert($serialize->read() !== $write);
        assert($serialize->unlink());
        assert(Core\File\Serialize::getClass($storage.'/serialize.txt',['create'=>true]) === Core\File\Serialize::class);

        // session
        $storageSession = '[storage]/session/main';
        $f = new Core\File\Session($storageSession.'/abcdef',['create'=>true]);
        $f->write([1,2,3]);
        assert(Core\File::new($f->path()) instanceof Core\File\Session);
        assert($f->read() === [1,2,3]);
        assert($f->sessionSid() === 'abcdef');
        assert(!empty($f->sessionData()));
        assert($f->sessionWrite(serialize([3,4,5])));
        assert($f->sessionUpdateTimestamp());
        assert($f->sessionDestroy());
        assert(!empty(Core\File\Session::sessionDir(Base\Finder::normalize($storageSession),'test')));
        assert(!empty(Core\File\Session::sessionPath(Base\Finder::normalize($storageSession),'test','abcde')));
        assert(!Core\File\Session::sessionExists(Base\Finder::normalize($storageSession),'test','abcde'));
        assert(Core\File\Session::sessionCreate(Base\Finder::normalize($storageSession),'test','abcde') instanceof Core\File\Session);
        assert(Core\File\Session::sessionRead(Base\Finder::normalize($storageSession),'test','abcde') instanceof Core\File\Session);
        assert(Core\File\Session::sessionGarbageCollect(Base\Finder::normalize($storageSession),'test',1000) === 0);
        assert(Base\Dir::emptyAndUnlink($storageSession));

        // text
        assert($text instanceof Core\File\Text);
        assert($text2 instanceof Core\File\Text);

        // txt
        assert($core instanceof Core\File);
        assert($text2 instanceof Core\File\Txt);
        assert(is_resource($text->resource()));
        assert(!$text->isFileUploaded());
        assert(count($text->lines()) === 4);
        assert($text->seek() === $text);
        assert($text->line() === 'lorem ipsum lorem ipsum');
        $i = 0;
        assert($text->seekRewind()->lineRef(true,true,$i) === 'lorem ipsum lorem ipsum');
        assert($text->lineRef(true,true,$i) === 'lorem ipsum lorem ipsum2');

        // video
        assert($video instanceof Core\File\Video);
        assert($video->mime() === 'inode/x-empty; charset=binary');

        // xml
        assert($xml instanceof Core\File\Xml);
        assert($xml->write('<?xml'));
        assert($xml->mime() === 'text/xml; charset=us-ascii');

        // zip
        assert($zip instanceof Core\File\Zip);
        assert($zip->mime() === 'application/zip; charset=binary');
        assert($zip->archive() instanceof \ZipArchive);
        assert(count($zip->all()) === 9);
        assert($zip->extract($storage.'/extract'));
        
        // problème avec commit du zip sous Windows
        if(!Base\Server::isWindows())
        {
            assert($newZip->all() === []);
            assert($newZip->addFile($newXml));
            assert($newZip->addFile($video));
            assert(count($newZip->all()) === 2);
            assert($newZip->commit());
            assert($newZip->extract($storage.'/extract2'));
        }

        // cleanup
        Base\Dir::empty('[assertCurrent]');

        return true;
    }
}
?>
# Quid\Orm
[![Release](https://img.shields.io/github/v/release/quidphp/core)](https://packagist.org/packages/quidphp/core)
[![License](https://img.shields.io/github/license/quidphp/core)](https://github.com/quidphp/core/blob/master/LICENSE)
[![PHP Version](https://img.shields.io/packagist/php-v/quidphp/core)](https://www.php.net)
[![Style CI](https://styleci.io/repos/203755370/shield)](https://styleci.io)
[![Code Size](https://img.shields.io/github/languages/code-size/quidphp/core)](https://github.com/quidphp/core)

## About
**quidphp\core** provides PHP, JS and SCSS components for QuidPHP application and CMS. It is part of the [QuidPHP](https://github.com/quidphp/project) package. 

## License
**quidphp\core** is available as an open-source software under the [MIT license](LICENSE).

## Installation
**quidphp\core** can be easily installed with [Composer](https://getcomposer.org). It is available on [Packagist](https://packagist.org/packages/quidphp/core).
``` bash
$ composer require quidphp/core
```
Once installed, the **Quid\Core** namespace will be available within your PHP application.

## Requirement
**quidphp\core** requires the following:
- PHP 7.2+ with fileinfo, curl, openssl, posix, PDO and pdo_mysql

## Dependency
**quidphp\core** has the following dependency:
- [quidphp/routing](https://github.com/quidphp/routing)
- [quidphp/orm](https://github.com/quidphp/orm)
- [quidphp/main](https://github.com/quidphp/main)
- [quidphp/base](https://github.com/quidphp/base)
- [verot/class.upload.php](https://github.com/verot/class.upload.php)
- [phpmailer/phpmailer](https://github.com/phpmailer/phpmailer)
- [tedivm/jshrink](https://github.com/tedious/JShrink)
- [leafo/scssphp](https://github.com/leafo/scssphp)

## Testing
**quidphp\core** testsuite can be run by creating a new [quidphp/project](https://github.com/quidphp/project). All tests and assertions are part of the [quidphp/test](https://github.com/quidphp/test) repository.

## Comment
**quidphp\core** code is commented and all methods are explained. However, the method and property comments are currently written in French.

## Convention
**quidphp\core** is built on the following conventions:
- *Traits*: Traits filenames start with an underscore (_).
- *Coding*: No curly braces are used in a IF statement if the condition can be resolved in only one statement.
- *Type*: Files, function arguments and return types are strict typed.
- *Config*: A special $config static property exists in all classes. This property gets recursively merged with the parents' property on initialization.

## Overview
**quidphp\core** contains 263 classes and traits. Here is an overview:
- [App](src/App)
    - [Error](src/App/Error.php)
    - [Home](src/App/Home.php)
    - [Robots](src/App/Robots.php)
    - [Sitemap](src/App/Sitemap.php)
- [Boot](src/Boot.php)
- [BootException](src/BootException.php)
- [Cell](src/Cell.php)
    - [Date](src/Cell/Date.php)
    - [Enum](src/Cell/Enum.php)
    - [Files](src/Cell/Files.php)
    - [Floating](src/Cell/Floating.php)
    - [Integer](src/Cell/Integer.php)
    - [JsonArray](src/Cell/JsonArray.php)
    - [JsonArrayRelation](src/Cell/JsonArrayRelation.php)
    - [Media](src/Cell/Media.php)
    - [Medias](src/Cell/Medias.php)
    - [Primary](src/Cell/Primary.php)
    - [Relation](src/Cell/Relation.php)
    - [Set](src/Cell/Set.php)
    - [UserPasswordReset](src/Cell/UserPasswordReset.php)
    - [Video](src/Cell/Video.php)
- [Cells](src/Cells.php)
- [Cms](src/Cms)
    - [About](src/Cms/About.php)
    - [Account](src/Cms/Account.php)
    - [AccountChangePassword](src/Cms/AccountChangePassword.php)
    - [AccountChangePasswordSubmit](src/Cms/AccountChangePasswordSubmit.php)
    - [ActivatePassword](src/Cms/ActivatePassword.php)
    - [Error](src/Cms/Error.php)
    - [General](src/Cms/General.php)
    - [GeneralDelete](src/Cms/GeneralDelete.php)
    - [GeneralExport](src/Cms/GeneralExport.php)
    - [GeneralExportDialog](src/Cms/GeneralExportDialog.php)
    - [GeneralRelation](src/Cms/GeneralRelation.php)
    - [GeneralTruncate](src/Cms/GeneralTruncate.php)
    - [Home](src/Cms/Home.php)
    - [HomeSearch](src/Cms/HomeSearch.php)
    - [Login](src/Cms/Login.php)
    - [LoginSubmit](src/Cms/LoginSubmit.php)
    - [Logout](src/Cms/Logout.php)
    - [Register](src/Cms/Register.php)
    - [RegisterSubmit](src/Cms/RegisterSubmit.php)
    - [ResetPassword](src/Cms/ResetPassword.php)
    - [ResetPasswordSubmit](src/Cms/ResetPasswordSubmit.php)
    - [Robots](src/Cms/Robots.php)
    - [Sitemap](src/Cms/Sitemap.php)
    - [Specific](src/Cms/Specific.php)
    - [SpecificAdd](src/Cms/SpecificAdd.php)
    - [SpecificAddSubmit](src/Cms/SpecificAddSubmit.php)
    - [SpecificCalendar](src/Cms/SpecificCalendar.php)
    - [SpecificDelete](src/Cms/SpecificDelete.php)
    - [SpecificDispatch](src/Cms/SpecificDispatch.php)
    - [SpecificDownload](src/Cms/SpecificDownload.php)
    - [SpecificDuplicate](src/Cms/SpecificDuplicate.php)
    - [SpecificRelation](src/Cms/SpecificRelation.php)
    - [SpecificSubmit](src/Cms/SpecificSubmit.php)
    - [SpecificTableRelation](src/Cms/SpecificTableRelation.php)
    - [SpecificUserWelcome](src/Cms/SpecificUserWelcome.php)
    - [_common](src/Cms/_common.php)
    - [_export](src/Cms/_export.php)
    - [_general](src/Cms/_general.php)
    - [_module](src/Cms/_module.php)
    - [_nobody](src/Cms/_nobody.php)
    - [_page](src/Cms/_page.php)
    - [_specific](src/Cms/_specific.php)
    - [_specificSubmit](src/Cms/_specificSubmit.php)
    - [_template](src/Cms/_template.php)
- [Col](src/Col.php)
    - [Active](src/Col/Active.php)
    - [Auto](src/Col/Auto.php)
    - [Boolean](src/Col/Boolean.php)
    - [Context](src/Col/Context.php)
    - [ContextType](src/Col/ContextType.php)
    - [CountCommit](src/Col/CountCommit.php)
    - [Date](src/Col/Date.php)
    - [DateAdd](src/Col/DateAdd.php)
    - [DateLogin](src/Col/DateLogin.php)
    - [DateModify](src/Col/DateModify.php)
    - [Email](src/Col/Email.php)
    - [Enum](src/Col/Enum.php)
    - [Error](src/Col/Error.php)
    - [Excerpt](src/Col/Excerpt.php)
    - [Files](src/Col/Files.php)
    - [Floating](src/Col/Floating.php)
    - [Fragment](src/Col/Fragment.php)
    - [Integer](src/Col/Integer.php)
    - [Json](src/Col/Json.php)
    - [JsonArray](src/Col/JsonArray.php)
    - [JsonArrayRelation](src/Col/JsonArrayRelation.php)
    - [JsonExport](src/Col/JsonExport.php)
    - [Media](src/Col/Media.php)
    - [Medias](src/Col/Medias.php)
    - [Phone](src/Col/Phone.php)
    - [Pointer](src/Col/Pointer.php)
    - [Primary](src/Col/Primary.php)
    - [Relation](src/Col/Relation.php)
    - [Request](src/Col/Request.php)
    - [RequestIp](src/Col/RequestIp.php)
    - [Serialize](src/Col/Serialize.php)
    - [Session](src/Col/Session.php)
    - [Set](src/Col/Set.php)
    - [Slug](src/Col/Slug.php)
    - [SlugPath](src/Col/SlugPath.php)
    - [Textarea](src/Col/Textarea.php)
    - [Timezone](src/Col/Timezone.php)
    - [UserActive](src/Col/UserActive.php)
    - [UserAdd](src/Col/UserAdd.php)
    - [UserCommit](src/Col/UserCommit.php)
    - [UserModify](src/Col/UserModify.php)
    - [UserPassword](src/Col/UserPassword.php)
    - [UserPasswordReset](src/Col/UserPasswordReset.php)
    - [UserRole](src/Col/UserRole.php)
    - [Username](src/Col/Username.php)
    - [Video](src/Col/Video.php)
    - [Yes](src/Col/Yes.php)
- [Cols](src/Cols.php)
- [Com](src/Com.php)
- [Db](src/Db.php)
- [Error](src/Error.php)
- [File](src/File.php)
    - [Audio](src/File/Audio.php)
    - [Binary](src/File/Binary.php)
    - [Cache](src/File/Cache.php)
    - [Calendar](src/File/Calendar.php)
    - [Css](src/File/Css.php)
    - [Csv](src/File/Csv.php)
    - [Doc](src/File/Doc.php)
    - [Dump](src/File/Dump.php)
    - [Email](src/File/Email.php)
    - [Error](src/File/Error.php)
    - [Font](src/File/Font.php)
    - [Html](src/File/Html.php)
    - [Image](src/File/Image.php)
    - [ImageRaster](src/File/ImageRaster.php)
    - [ImageVector](src/File/ImageVector.php)
    - [Js](src/File/Js.php)
    - [Json](src/File/Json.php)
    - [Log](src/File/Log.php)
    - [Pdf](src/File/Pdf.php)
    - [Php](src/File/Php.php)
    - [Queue](src/File/Queue.php)
    - [Serialize](src/File/Serialize.php)
    - [Session](src/File/Session.php)
    - [Text](src/File/Text.php)
    - [Txt](src/File/Txt.php)
    - [Video](src/File/Video.php)
    - [Xml](src/File/Xml.php)
    - [Zip](src/File/Zip.php)
- [Files](src/Files.php)
- [Flash](src/Flash.php)
- [Lang](src/Lang.php)
    - [En](src/Lang/En.php)
    - [Fr](src/Lang/Fr.php)
    - [_overload](src/Lang/_overload.php)
- [Nav](src/Nav.php)
- [Redirection](src/Redirection.php)
- [Request](src/Request.php)
- [RequestHistory](src/RequestHistory.php)
- [Response](src/Response.php)
- [Role](src/Role.php)
    - [Admin](src/Role/Admin.php)
    - [Contributor](src/Role/Contributor.php)
    - [Cron](src/Role/Cron.php)
    - [Editor](src/Role/Editor.php)
    - [Nobody](src/Role/Nobody.php)
    - [Shared](src/Role/Shared.php)
    - [SubAdmin](src/Role/SubAdmin.php)
    - [User](src/Role/User.php)
- [Roles](src/Roles.php)
- [Route](src/Route.php)
    - [Account](src/Route/Account.php)
    - [AccountChangePassword](src/Route/AccountChangePassword.php)
    - [AccountChangePasswordSubmit](src/Route/AccountChangePasswordSubmit.php)
    - [AccountSubmit](src/Route/AccountSubmit.php)
    - [ActivatePassword](src/Route/ActivatePassword.php)
    - [Error](src/Route/Error.php)
    - [Home](src/Route/Home.php)
    - [Login](src/Route/Login.php)
    - [LoginSubmit](src/Route/LoginSubmit.php)
    - [Logout](src/Route/Logout.php)
    - [Register](src/Route/Register.php)
    - [RegisterSubmit](src/Route/RegisterSubmit.php)
    - [ResetPassword](src/Route/ResetPassword.php)
    - [ResetPasswordSubmit](src/Route/ResetPasswordSubmit.php)
    - [Robots](src/Route/Robots.php)
    - [Sitemap](src/Route/Sitemap.php)
    - [_calendar](src/Route/_calendar.php)
    - [_colRelation](src/Route/_colRelation.php)
    - [_download](src/Route/_download.php)
    - [_formSubmit](src/Route/_formSubmit.php)
    - [_general](src/Route/_general.php)
    - [_generalPager](src/Route/_generalPager.php)
    - [_generalRelation](src/Route/_generalRelation.php)
    - [_generalSegment](src/Route/_generalSegment.php)
    - [_nobody](src/Route/_nobody.php)
    - [_relation](src/Route/_relation.php)
    - [_search](src/Route/_search.php)
    - [_specific](src/Route/_specific.php)
    - [_specificNav](src/Route/_specificNav.php)
    - [_specificPrimary](src/Route/_specificPrimary.php)
    - [_specificRelation](src/Route/_specificRelation.php)
    - [_tableRelation](src/Route/_tableRelation.php)
- [Routes](src/Routes.php)
- [Row](src/Row.php)
    - [Email](src/Row/Email.php)
    - [Lang](src/Row/Lang.php)
    - [Log](src/Row/Log.php)
    - [LogCron](src/Row/LogCron.php)
    - [LogEmail](src/Row/LogEmail.php)
    - [LogError](src/Row/LogError.php)
    - [LogHttp](src/Row/LogHttp.php)
    - [LogSql](src/Row/LogSql.php)
    - [QueueEmail](src/Row/QueueEmail.php)
    - [Redirection](src/Row/Redirection.php)
    - [Session](src/Row/Session.php)
    - [User](src/Row/User.php)
    - [_log](src/Row/_log.php)
    - [_new](src/Row/_new.php)
    - [_queue](src/Row/_queue.php)
- [Rows](src/Rows.php)
- [RowsIndex](src/RowsIndex.php)
- [Segment](src/Segment)
    - [_boolean](src/Segment/_boolean.php)
    - [_col](src/Segment/_col.php)
    - [_colRelation](src/Segment/_colRelation.php)
    - [_cols](src/Segment/_cols.php)
    - [_direction](src/Segment/_direction.php)
    - [_filter](src/Segment/_filter.php)
    - [_int](src/Segment/_int.php)
    - [_limit](src/Segment/_limit.php)
    - [_order](src/Segment/_order.php)
    - [_orderColRelation](src/Segment/_orderColRelation.php)
    - [_orderTableRelation](src/Segment/_orderTableRelation.php)
    - [_page](src/Segment/_page.php)
    - [_pointer](src/Segment/_pointer.php)
    - [_primaries](src/Segment/_primaries.php)
    - [_primary](src/Segment/_primary.php)
    - [_selected](src/Segment/_selected.php)
    - [_slug](src/Segment/_slug.php)
    - [_str](src/Segment/_str.php)
    - [_table](src/Segment/_table.php)
    - [_timestamp](src/Segment/_timestamp.php)
    - [_timestampMonth](src/Segment/_timestampMonth.php)
    - [_yes](src/Segment/_yes.php)
- [Service](src/Service.php)
    - [ClassUpload](src/Service/ClassUpload.php)
    - [JShrink](src/Service/JShrink.php)
    - [Ldap](src/Service/Ldap.php)
    - [PhpConcatenator](src/Service/PhpConcatenator.php)
    - [PhpMailer](src/Service/PhpMailer.php)
    - [ScssPhp](src/Service/ScssPhp.php)
- [ServiceMailer](src/ServiceMailer.php)
- [ServiceRequest](src/ServiceRequest.php)
- [ServiceVideo](src/ServiceVideo.php)
- [Services](src/Services.php)
- [Session](src/Session.php)
- [Table](src/Table.php)
- [Tables](src/Tables.php)
- [Widget](src/Widget.php)
    - [Calendar](src/Widget/Calendar.php)
- [_access](src/_access.php)
- [_bootAccess](src/_bootAccess.php)
- [_dbAccess](src/_dbAccess.php)
- [_fullAccess](src/_fullAccess.php)
- [_routeAttr](src/_routeAttr.php)
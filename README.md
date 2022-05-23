# QuidPHP/Core
[![Release](https://img.shields.io/github/v/release/quidphp/core)](https://packagist.org/packages/quidphp/core)
[![License](https://img.shields.io/github/license/quidphp/core)](https://github.com/quidphp/core/blob/master/LICENSE)
[![PHP Version](https://img.shields.io/packagist/php-v/quidphp/core)](https://www.php.net)
[![Style CI](https://styleci.io/repos/203755370/shield)](https://styleci.io)
[![Code Size](https://img.shields.io/github/languages/code-size/quidphp/core)](https://github.com/quidphp/core)

## About
**QuidPHP/Core** is a PHP library that provides an extendable platform to create dynamic applications. It is part of the [QuidPHP](https://github.com/quidphp/project) package. 

## License
**QuidPHP/Core** is available as an open-source software under the [MIT license](LICENSE).

## Documentation
**QuidPHP/Core** documentation is available at [QuidPHP/Docs](https://github.com/quidphp/docs).

## Installation
**QuidPHP/Core** can be easily installed with [Composer](https://getcomposer.org). It is available on [Packagist](https://packagist.org/packages/quidphp/core).
``` bash
$ composer require quidphp/core
```
Once installed, the **Quid\Core** namespace will be available within your PHP application.

## Requirement
**QuidPHP/Core** requires the following:
- PHP 8.1:
- Mysql (>= 8.0) or MariaDB (>= 10.4) database
- Apache or Nginx server (running on MacOs, Linux or Windows)
- All requirements of [quidphp/orm](https://github.com/quidphp/orm)
- All requirements of [quidphp/routing](https://github.com/quidphp/routing)

## Dependency
**QuidPHP/Core** has the following dependencies:
- [quidphp/orm](https://github.com/quidphp/orm) - Quid\Orm - PHP library that provides database access and a comprehensive ORM
- [quidphp/routing](https://github.com/quidphp/routing) - Quid\Routing - PHP library that provides a route matching and triggering procedure
- [phpmailer/phpmailer](https://github.com/phpmailer/phpmailer) - PHPMailer\PHPMailer - The classic email sending library for PHP
- [scssphp/scssphp](https://github.com/scssphp/scssphp) - ScssPhp\ScssPhp - SCSS compiler written in PHP
- [tedivm/jshrink](https://github.com/tedious/JShrink) - JShrink - Javascript Minifier written in PHP
- [verot/class.upload.php](https://github.com/verot/class.upload.php) - Verot\Upload - A popular PHP class used for resizing images

All dependencies will be resolved by using the [Composer](https://getcomposer.org) installation process.

## Comment
**QuidPHP/Core** code is commented and all methods are explained (in French).

## Convention
**QuidPHP/Core** is built on the following conventions:
- *Auto-alias*: All class names that finishes by Alias will resolve to the existing class if no alias exists. Exemple: MyRole extents RoleAlias -> will resolve to Role if no alias is found.
- *Class overloading*: It is possible to retrieve an overloaded class with the same name but higher in the namespace hierarchy. We do this by using the static method $class::getOverloadClass(). Exemple Quid\Orm\Col::getOverloadClass() will return Quid\Core\Col.
- *Coding*: No curly braces are used in a IF statement if the condition can be resolved in only one statement.
- *Config*: A special $config static property exists in all classes. This property gets recursively merged with the parents' property on initialization.
- *Core overloading*: Using auto-alias and class overloading, it is possible to effectively replace all classes within Quid\Core by classes within an application directory.
- *Traits*: Traits filenames start with an underscore (_).
- *Type*: Files, function arguments and return types are strict typed.

### Overview
**QuidPHP/Core** contains 115 classes and traits. Here is an overview:
- [Boot](src/Boot.php) - Abstract class for boot which is the object that bootstraps the application
- [Cell](src/Cell.php) - Extended class to represent an existing cell within a row
    - [Date](src/Cell/Date.php) - Class to work with a cell containing a date value
    - [Enum](src/Cell/Enum.php) - Class to manage a cell containing a single relation (enum)
    - [Files](src/Cell/Files.php) - Abstract class extended by the media and medias cells
    - [Integer](src/Cell/Integer.php) - Class to manage a cell containing an integer value
    - [Media](src/Cell/Media.php) - Class to work with a cell containing a value which is a link to a file
    - [Medias](src/Cell/Medias.php) - Class to manage a cell containing a value which is a link to many files
    - [Num](src/Cell/Num.php) - Class to manage a cell containing a numeric value
    - [Primary](src/Cell/Primary.php) - Class for dealing with a cell of a column which has an auto increment primary key
    - [Relation](src/Cell/Relation.php) - Abstract class extended by the enum and set cells
    - [Set](src/Cell/Set.php) - Class to manage a cell containing many relations separated by comma (set)
    - [UserPasswordReset](src/Cell/UserPasswordReset.php) - Class to work with a password reset cell within a user table
    - [UserRole](src/Cell/UserRole.php) - Class to work with the user role cell within a user table
- [Cells](src/Cells.php) - Extended class for a collection of many cells within a same row
- [Col](src/Col.php) - Extended class to represent an existing column within a table
    - [Active](src/Col/Active.php) - Class for the active column - extends the Yes column class
    - [Boolean](src/Col/Boolean.php) - Class for the boolean column - a simple yes/no enum relation
    - [Context](src/Col/Context.php) - Class for the context column, updates itself automatically on commit
    - [ContextType](src/Col/ContextType.php) - Class for the contextType column, a checkbox set relation with all boot types
    - [CountCommit](src/Col/CountCommit.php) - Class for the countCommit column, increments itself automatically on commit
    - [Date](src/Col/Date.php) - Class for a date column, supports many date formats
    - [DateAdd](src/Col/DateAdd.php) - Class for a column which stores the current timestamp when the row is inserted
    - [DateLogin](src/Col/DateLogin.php) - Class for a column which stores the current timestamp when the user logs in
    - [DateModify](src/Col/DateModify.php) - Class for a column which stores the current timestamp when the row is updated
    - [Email](src/Col/Email.php) - Class for a column managing email
    - [Enum](src/Col/Enum.php) - Class for a column containing an enum relation (one)
    - [EnvType](src/Col/EnvType.php) - Class for the envType column, updates itself automatically on commit
    - [Error](src/Col/Error.php) - Class for a column that manages an error object
    - [Files](src/Col/Files.php) - Abstract class extended by the media and medias cols
    - [Floating](src/Col/Floating.php) - Class for a column which deals with floating values
    - [Integer](src/Col/Integer.php) - Class for a column which deals with integer values
    - [Json](src/Col/Json.php) - Class for a column which manages json values
    - [Media](src/Col/Media.php) - Class to work with a column containing a value which is a link to a file
    - [Medias](src/Col/Medias.php) - Class to work with a column containing a value which is a link to many files
    - [Num](src/Col/Num.php) - Class for a column which deals with numeric values (string, float or int)
    - [Pointer](src/Col/Pointer.php) - Class for a column which the value is a pointer to another row in the database
    - [Primary](src/Col/Primary.php) - Class for dealing with a column which has an auto increment primary key
    - [Relation](src/Col/Relation.php) - Abstract class extended by the enum and set columns
    - [Request](src/Col/Request.php) - Class for a column that manages a request object
    - [RequestIp](src/Col/RequestIp.php) - Class for a column which applies the current request ip as value on commit
    - [Serialize](src/Col/Serialize.php) - Class for a column which should serialize its value
    - [Session](src/Col/Session.php) - Class for a column which should store current session id
    - [Set](src/Col/Set.php) - Class for a column containing a set relation (many)
    - [Timezone](src/Col/Timezone.php) - Class for a column which is an enum relation to the PHP timezone list
    - [Uri](src/Col/Uri.php) - Class for a column managing an uri (relative or absolute)
    - [UriAbsolute](src/Col/UriAbsolute.php) - Class for a column managing an absolute uri
    - [UriRelative](src/Col/UriRelative.php) - Class for a column managing a relative uri
    - [UserActive](src/Col/UserActive.php) - Class for the column which manages the active field for the user row
    - [UserAdd](src/Col/UserAdd.php) - Class for a column which stores the current user id on insert
    - [UserCommit](src/Col/UserCommit.php) - Class for a column which stores the current user id on commit
    - [UserModify](src/Col/UserModify.php) - Class for a column which stores the current user id on update
    - [UserPassword](src/Col/UserPassword.php) - Class for the column which manages the password field for the user row
    - [UserPasswordReset](src/Col/UserPasswordReset.php) - Class for the userPasswordReset column of a user row
    - [UserRole](src/Col/UserRole.php) - Class for the column which manages the role field for the user row
    - [Username](src/Col/Username.php) - Class for the username column of a user row
    - [Yes](src/Col/Yes.php) - Class for the yes column - a simple yes checkbox
- [Cols](src/Cols.php) - Extended class for a collection of many columns within a same table
- [Com](src/Com.php) - Extended class that provides the logic to store communication messages
- [Db](src/Db.php) - Extended class used to query the database
- [Error](src/Error.php) - Extended class used as an error handler
- [File](src/File)
    - [Css](src/File/Css.php) - Class for a css or scss file
    - [ImageRaster](src/File/ImageRaster.php) - Class for a pixelated image file
    - [Js](src/File/Js.php) - Class for a js file
    - [Php](src/File/Php.php) - Class for a php file
- [Flash](src/Flash.php) - Extended class for a collection containing flash-like data (delete on read)
- [Lang](src/Lang.php) - Extended class for a collection object containing language texts and translations
    - [En](src/Lang/En.php) - English language content used by this namespace
    - [Fr](src/Lang/Fr.php) - French language content used by this namespace
- [Request](src/Request.php) - Extended class with methods to manage an HTTP request
- [Role](src/Role.php) - Extended abstract class that provides more advanced logic for a role
- [Route](src/Route.php) - Extended abstract class for a route that acts as both a View and a Controller
    - [CliCompile](src/Route/CliCompile.php) - Class for a cli route to compile assets (js and css)
    - [CliPreload](src/Route/CliPreload.php) - Abstract class for a cli route to generate a preload version of the PHP application
    - [CliSessionGc](src/Route/CliSessionGc.php) - Abstract class for a cli route to remove expired sessions
    - [CliVersion](src/Route/CliVersion.php) - Abstract class for the version route, accessible via the cli
    - [Error](src/Route/Error.php) - Abstract class for an error route
    - [Home](src/Route/Home.php) - Abstract class for a home route
    - [Robots](src/Route/Robots.php) - Abstract class for a robots.txt route
    - [Sitemap](src/Route/Sitemap.php) - Abstract class for automated sitemap.xml route
    - [_cli](src/Route/_cli.php) - Trait that provides some initial configuration for a cli route
    - [_cliClear](src/Route/_cliClear.php) - Trait for a cli route to remove log files and truncate tables
    - [_cliLive](src/Route/_cliLive.php) - Trait that provides some methods for a cli script which loops
    - [_cliOpt](src/Route/_cliOpt.php) - Trait that provides some methods to manage opt in a cli application
- [Row](src/Row.php) - Extended class to represent an existing row within a table
    - [CacheRoute](src/Row/CacheRoute.php) - Class to store rendered route caches
    - [Email](src/Row/Email.php) - Class to deal with a row of the email table, contains the emailModels
    - [Lang](src/Row/Lang.php) - Class to work with a row of the lang table, contains the text and translations
    - [Log](src/Row/Log.php) - Class to represent a row of the log table, stores user activities
    - [LogCron](src/Row/LogCron.php) - Class to represent a row of the logCron table, stores cron jobs results
    - [LogEmail](src/Row/LogEmail.php) - Class to represent a row of the logEmail table, stores emails sent
    - [LogError](src/Row/LogError.php) - Class to represent a row of the logError table, stores error objects
    - [LogHttp](src/Row/LogHttp.php) - Class to represent a row of the logHttp table, stores bad HTTP requests
    - [LogSql](src/Row/LogSql.php) - Class to represent a row of the logSql table, stores sql queries
    - [QueueEmail](src/Row/QueueEmail.php) - Class to deal with a row of the queueEmail table, stores the email to send
    - [Redirection](src/Row/Redirection.php) - Class to work with a row of the redirection table
    - [Session](src/Row/Session.php) - Class to work with a row of the session table
    - [User](src/Row/User.php) - Class for a row of the user table
    - [_emailModel](src/Row/_emailModel.php) - Trait with methods relative to sending emails from models
    - [_log](src/Row/_log.php) - Trait that adds log-related methods to a row
    - [_queue](src/Row/_queue.php) - Trait that adds queuing-related methods to a row
- [Rows](src/Rows.php) - Extended class for a collection of many rows within a same table
- [Service](src/Service)
    - [ClassUpload](src/Service/ClassUpload.php) - Class that provides methods to use verot/class.upload.php for resizing images
    - [Composer](src/Service/Composer.php) - Class that grants some methods related to the composer autoloader
    - [JShrink](src/Service/JShrink.php) - Class that provides methods to use tedivm/jshrink for minifying JavaScript
    - [PhpMailer](src/Service/PhpMailer.php) - Class that provides methods to use phpmailer/phpmailer in order to send emails
    - [PhpPreload](src/Service/PhpPreload.php) - Class used for concatenating a bunch of php files within a single one, for use as preloading
    - [ScssPhp](src/Service/ScssPhp.php) - Class that grants methods to use scssphp/scssphp for compiling scss files
- [ServiceMailer](src/ServiceMailer.php) - Extended abstract class with basic methods that needs to be extended by a mailing service
- [Session](src/Session.php) - Extended class that adds session support for user
- [Table](src/Table.php) - Extended class to represent an existing table within a database
- [_access](src/_access.php) - Trait that provides methods to useful objects related to the Boot
- [_bootAccess](src/_bootAccess.php) - Trait that provides methods to access the Boot object
- [_dbAccess](src/_dbAccess.php) - Trait that provides a method to access the current database
- [_fullAccess](src/_fullAccess.php) - Trait that provides all access methods related to the Boot
	
## Testing
**QuidPHP/Core** contains 18 test classes:
- [Boot](test/Boot.php) - Class for testing Quid\Core\Boot
- [Cell](test/Cell.php) - Class for testing Quid\Core\Cell
- [Cells](test/Cells.php) - Class for testing Quid\Core\Cells
- [Col](test/Col.php) - Class for testing Quid\Core\Col
- [Cols](test/Cols.php) - Class for testing Quid\Core\Cols
- [Com](test/Com.php) - Class for testing Quid\Core\Com
- [Db](test/Db.php) - Class for testing Quid\Core\Db
- [Error](test/Error.php) - Class for testing Quid\Core\Error
- [Lang](test/Lang.php) - Class for testing Quid\Core\Lang
- [Request](test/Request.php) - Class for testing Quid\Core\Request
- [Role](test/Role.php) - Class for testing Quid\Core\Role
- [Route](test/Route.php) - Class for testing Quid\Core\Route
- [Row](test/Row.php) - Class for testing Quid\Core\Row
- [Rows](test/Rows.php) - Class for testing Quid\Core\Rows
- [ServiceMailer](test/ServiceMailer.php) - Class for testing Quid\Core\ServiceMailer
- [Session](test/Session.php) - Class for testing Quid\Core\Session
- [Suite](test/Suite)
    - [BootCore](test/Suite/BootCore.php) - Class for booting the Quid\Core testsuite
- [Table](test/Table.php) - Class for testing Quid\Core\Table

**QuidPHP/Core** testsuite can be run by creating a new [QuidPHP/Assert](https://github.com/quidphp/assert) project.
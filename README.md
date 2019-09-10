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
**QuidPHP/Core** documentation is being written. Once ready, it will be available at https://quidphp.github.io/project.

## Installation
**QuidPHP/Core** can be easily installed with [Composer](https://getcomposer.org). It is available on [Packagist](https://packagist.org/packages/quidphp/core).
``` bash
$ composer require quidphp/core
```
Once installed, the **Quid\Core** namespace will be available within your PHP application.

## Requirement
**QuidPHP/Core** requires the following:
- PHP 7.2+ with fileinfo, curl, openssl, PDO and pdo_mysql

## Dependency
**QuidPHP/Core** has the following dependencies:
- [quidphp/base](https://github.com/quidphp/base) | Quid\Base - PHP library that provides a set of low-level static methods
- [quidphp/main](https://github.com/quidphp/main) | Quid\Main - PHP library that provides a set of base objects and collections 
- [quidphp/orm](https://github.com/quidphp/orm) | Quid\Orm - PHP library that provides database access and a comprehensive Object-Relational Mapper
- [quidphp/routing](https://github.com/quidphp/routing) | Quid\Routing - PHP library that provides a simple route matching and triggering procedure
- [verot/class.upload.php](https://github.com/verot/class.upload.php) | Verot\Upload - A popular PHP class used for resizing images
- [phpmailer/phpmailer](https://github.com/phpmailer/phpmailer) | PHPMailer\PHPMailer - The classic email sending library for PHP
- [tedivm/jshrink](https://github.com/tedious/JShrink) | JShrink - Javascript Minifier built in PHP
- [scssphp/scssphp](https://github.com/scssphp/scssphp) | ScssPhp\ScssPhp - SCSS compiler written in PHP

All dependencies will be resolved by using the [Composer](https://getcomposer.org) installation process.

## Comment
**QuidPHP/Core** code is commented and all methods are explained. However, most of the comments are currently written in French.

## Convention
**QuidPHP/Core** is built on the following conventions:
- *Traits*: Traits filenames start with an underscore (_).
- *Coding*: No curly braces are used in a IF statement if the condition can be resolved in only one statement.
- *Type*: Files, function arguments and return types are strict typed.
- *Config*: A special $config static property exists in all classes. This property gets recursively merged with the parents' property on initialization.
- *Auto-alias*: All class names that finishes by Alias will resolve to the existing class if no alias exists. Exemple: MyRole extents RoleAlias -> will resolve to Role if no alias is found.
- *Class overloading*: It is possible to retrieve an overloaded class with the same name but higher in the directory hierarchy. We do this by using the static method $class::getOverloadClass(). Exemple Quid\Orm\Col::getOverloadClass() will return Quid\Core\Col.
- *Core overloading*: Using auto-alias and class overloading, it is possible to effectively replace all classes within Quid\Core by classes within an application directory.

### Overview
**QuidPHP/Core** contains 211 classes and traits. Here is an overview:
- [Boot](src/Boot.php) | Abstract class for boot which is the object that bootstraps the application
- [Cell](src/Cell.php) | Extended class to represent an existing cell within a row
    - [Date](src/Cell/Date.php) | Class to work with a cell containing a date value
    - [Enum](src/Cell/Enum.php) | Class to manage a cell containing a single relation (enum)
    - [Files](src/Cell/Files.php) | Abstract class extended by the media and medias cells
    - [Floating](src/Cell/Floating.php) | Class to work with a cell containing a floating value
    - [Integer](src/Cell/Integer.php) | Class to manage a cell containing an integer value
    - [JsonArray](src/Cell/JsonArray.php) | Class to manage a cell containing a json array
    - [JsonArrayRelation](src/Cell/JsonArrayRelation.php) | Class to manage a cell containing a relation value to another cell containing a json array
    - [Media](src/Cell/Media.php) | Class to work with a cell containing a value which is a link to a file
    - [Medias](src/Cell/Medias.php) | Class to manage a cell containing a value which is a link to many files
    - [Primary](src/Cell/Primary.php) | Class for dealing with a cell of a column which has an auto increment primary key
    - [Relation](src/Cell/Relation.php) | Abstract class extended by the enum and set cells
    - [Set](src/Cell/Set.php) | Class to manage a cell containing many relations separated by comma (set)
    - [UserPasswordReset](src/Cell/UserPasswordReset.php) | Class to work with a password reset column within a user table
    - [Video](src/Cell/Video.php) | Class to manage a cell containing a video from a third-party service
- [Cells](src/Cells.php) | Extended class for a collection of many cells within a same row
- [Col](src/Col.php) | Extended class to represent an existing column within a table
    - [Active](src/Col/Active.php) | Class for the active column - a simple yes checkbox
    - [Auto](src/Col/Auto.php) | Class for the auto column, generate itself automatically using the data from other cells
    - [Boolean](src/Col/Boolean.php) | Class for the boolean column - a simple yes/no enum relation
    - [Context](src/Col/Context.php) | Class for the context column, updates itself automatically on commit
    - [ContextType](src/Col/ContextType.php) | Class for the contextType column, a checkbox set relation with all boot types
    - [CountCommit](src/Col/CountCommit.php) | Class for the countCommit column, increments itself automatically on commit
    - [Date](src/Col/Date.php) | Class for a date column, supports many date formats
    - [DateAdd](src/Col/DateAdd.php) | Class for the dateAdd column, current timestamp is added automatically on insert
    - [DateLogin](src/Col/DateLogin.php) | Class for the dateLogin column, current timestamp is applied automatically when the user logs in
    - [DateModify](src/Col/DateModify.php) | Class for the dateModify column, current timestamp is updated automatically on update
    - [Email](src/Col/Email.php) | Class for a column managing email
    - [Enum](src/Col/Enum.php) | Class for a column containing an enum relation (one)
    - [Error](src/Col/Error.php) | Class for a column that manages an error object as a value
    - [Excerpt](src/Col/Excerpt.php) | Class for a column which contains an excerpt of a longer value
    - [Files](src/Col/Files.php) | Abstract class extended by the media and medias cols
    - [Floating](src/Col/Floating.php) | Class for a column which deals with floating values
    - [Fragment](src/Col/Fragment.php) | Class for a column which contains URI fragments
    - [Integer](src/Col/Integer.php) | Class for a column which deals with integer values
    - [Json](src/Col/Json.php) | Class for a column which manages json values
    - [JsonArray](src/Col/JsonArray.php) | Class for a column which offers a special input for json values
    - [JsonArrayRelation](src/Col/JsonArrayRelation.php) | Class to manage a column containing a relation value to another column which is a jsonArray
    - [JsonExport](src/Col/JsonExport.php) | Class for a column that contains json which should be exported (similar to var_export)
    - [Media](src/Col/Media.php) | Class to work with a column containing a value which is a link to a file
    - [Medias](src/Col/Medias.php) | Class to work with a column containing a value which is a link to many files
    - [Phone](src/Col/Phone.php) | Class for a column managing phone numbers, automatically formats the value
    - [Pointer](src/Col/Pointer.php) | Class for a column which the value is a pointer to another row in the database
    - [Primary](src/Col/Primary.php) | Class for dealing with a column which has an auto increment primary key
    - [Relation](src/Col/Relation.php) | Abstract class extended by the enum and set columns
    - [Request](src/Col/Request.php) | Class for a column that manages a request object as a value
    - [RequestIp](src/Col/RequestIp.php) | Class for a column which applies the current request ip as value on commit
    - [Serialize](src/Col/Serialize.php) | Class for a column which should serialize its value
    - [Session](src/Col/Session.php) | Class for a column which should store current session id
    - [Set](src/Col/Set.php) | Class for a column containing a set relation (many)
    - [Slug](src/Col/Slug.php) | Class for a column dealing with URI slug
    - [SlugPath](src/Col/SlugPath.php) | Class for a column dealing with URI slug within a URI path
    - [Textarea](src/Col/Textarea.php) | Class for a column which is editable through a textarea input
    - [Timezone](src/Col/Timezone.php) | Class for a column which is an enum relation to the PHP timezone array
    - [UserActive](src/Col/UserActive.php) | Class for the column which manages the active field for the user row
    - [UserAdd](src/Col/UserAdd.php) | Class for the userAdd column, user_id is added automatically on insert
    - [UserCommit](src/Col/UserCommit.php) | Class for the userCommit column, user_id is added automatically on commit
    - [UserModify](src/Col/UserModify.php) | Class for the userModify column, user_id is added automatically on update
    - [UserPassword](src/Col/UserPassword.php) | Class for the column which manages the password field for the user row
    - [UserPasswordReset](src/Col/UserPasswordReset.php) | Class for the userPasswordReset column of a user row
    - [UserRole](src/Col/UserRole.php) | Class for the column which manages the role field for the user row
    - [Username](src/Col/Username.php) | Class for the username column of a user row
    - [Video](src/Col/Video.php) | Abstract class for a column containing a video from a third-party service
    - [Yes](src/Col/Yes.php) | Class for the yes column - a simple yes checkbox
- [Cols](src/Cols.php) | Extended class for a collection of many columns within a same table
- [Com](src/Com.php) | Extended class that provides the logic to store communication messages
- [Db](src/Db.php) | Extended class used to query the database
- [Error](src/Error.php) | Extended class used as an error handler
- [File](src/File.php) | Extended class for a basic file object
    - [Audio](src/File/Audio.php) | Class for an audio file (like mp3)
    - [Binary](src/File/Binary.php) | Abstract class for a binary file
    - [Cache](src/File/Cache.php) | Class for a cache storage file
    - [Calendar](src/File/Calendar.php) | Class for a calendar file (like ics)
    - [Css](src/File/Css.php) | Class for a css or scss file
    - [Csv](src/File/Csv.php) | Class for a csv file
    - [Doc](src/File/Doc.php) | Class for a doc file, like microsoft word
    - [Dump](src/File/Dump.php) | Class for file which contains an exported value (similar to var_export)
    - [Email](src/File/Email.php) | Class for a file which is an email (in json format)
    - [Error](src/File/Error.php) | Class for an error storage file
    - [Font](src/File/Font.php) | Class for a font file (like ttf)
    - [Html](src/File/Html.php) | Class for an html file
    - [Image](src/File/Image.php) | Abstract class for an image file (raster or vector)
    - [ImageRaster](src/File/ImageRaster.php) | Class for a pixelated image file
    - [ImageVector](src/File/ImageVector.php) | Class for a vector image file (like svg)
    - [Js](src/File/Js.php) | Class for a js file
    - [Json](src/File/Json.php) | Class for a json file
    - [Log](src/File/Log.php) | Class for a log storage file
    - [Pdf](src/File/Pdf.php) | Class for pdf file
    - [Php](src/File/Php.php) | Class for a php file
    - [Queue](src/File/Queue.php) | Class for a queue storage file
    - [Serialize](src/File/Serialize.php) | Class for a file with content that should be serialized
    - [Session](src/File/Session.php) | Class for a session storage file, which is serialized
    - [Text](src/File/Text.php) | Abstract class for a text file
    - [Txt](src/File/Txt.php) | Class for txt file (like txt)
    - [Video](src/File/Video.php) | Class for a video file (like mp4)
    - [Xml](src/File/Xml.php) | Class for an xml file
    - [Zip](src/File/Zip.php) | Class for a zip file
- [Files](src/Files.php) | Extended class for a collection containing many file objects
- [Flash](src/Flash.php) | Extended class for a collection containing flash-like data (delete on read)
- [Lang](src/Lang.php) | Extended class for a collection object containing language texts and translations
    - [En](src/Lang/En.php) | English language content used by this namespace
    - [Fr](src/Lang/Fr.php) | French language content used by this namespace
    - [_overload](src/Lang/_overload.php) | Trait which implements the overload logic for the lang classes
- [Nav](src/Nav.php) | Extended class for storing route navigation-related data
- [Redirection](src/Redirection.php) | Extended class managing a URI redirection array
- [Request](src/Request.php) | Extended class with methods to manage an HTTP request
- [RequestHistory](src/RequestHistory.php) | Extended class for a collection containing a history of requests
- [Response](src/Response.php) | Extended class with methods to manage an HTTP response
- [Role](src/Role.php) | Extended abstract class that provides more advanced logic for a role
    - [Admin](src/Role/Admin.php) | Class which contains the default configuration for the admin role
    - [Cron](src/Role/Cron.php) | Class which contains the default configuration for the cron role
    - [Nobody](src/Role/Nobody.php) | Class that issues default configuration for the nobody role
    - [Shared](src/Role/Shared.php) | Class that contains the default configuration for the shared role
    - [User](src/Role/User.php) | Class that contains the default configuration for the user role
- [Roles](src/Roles.php) | Extended class for a collection containing many roles
- [Route](src/Route.php) | Extended abstract class for a route that acts as both a View and a Controller
    - [Account](src/Route/Account.php) | Abstract class for an account route
    - [AccountChangePassword](src/Route/AccountChangePassword.php) | Abstract class for an account change password route
    - [AccountChangePasswordSubmit](src/Route/AccountChangePasswordSubmit.php) | Abstract class for an account change password submit route
    - [AccountSubmit](src/Route/AccountSubmit.php) | Abstract class for an account submit route
    - [ActivatePassword](src/Route/ActivatePassword.php) | Abstract class for a route that activates a password that was previously reset
    - [Error](src/Route/Error.php) | Abstract class for an error route
    - [Home](src/Route/Home.php) | Abstract class for a home route
    - [Login](src/Route/Login.php) | Abstract class for a login route
    - [LoginSubmit](src/Route/LoginSubmit.php) | Abstract class for a login submit route
    - [Logout](src/Route/Logout.php) | Abstract class for a logout route
    - [Register](src/Route/Register.php) | Abstract class for a register route
    - [RegisterSubmit](src/Route/RegisterSubmit.php) | Abstract class for a register submit route
    - [ResetPassword](src/Route/ResetPassword.php) | Abstract class for a reset password route
    - [ResetPasswordSubmit](src/Route/ResetPasswordSubmit.php) | Abstract class for a reset password submit route
    - [Robots](src/Route/Robots.php) | Abstract class for a robots route
    - [Sitemap](src/Route/Sitemap.php) | Abstract class for a sitemap route
    - [_calendar](src/Route/_calendar.php) | Trait that provides most methods to make a calendar route
    - [_colRelation](src/Route/_colRelation.php) | Trait that provides methods related to a column relation route
    - [_download](src/Route/_download.php) | Trait that provides most methods necessary to make a download route
    - [_formSubmit](src/Route/_formSubmit.php) | Trait that provides methods and logic necessary to make a form submit route
    - [_general](src/Route/_general.php) | Trait that provides most methods used for a general navigation route
    - [_generalPager](src/Route/_generalPager.php) | Trait that provides a method to make a general page navigator
    - [_generalRelation](src/Route/_generalRelation.php) | Trait that provides methods to make a filter from a relation
    - [_generalSegment](src/Route/_generalSegment.php) | Trait that provides some methods for a general navigation page
    - [_nobody](src/Route/_nobody.php) | Trait that provides a common method for a route when the user is not logged in
    - [_relation](src/Route/_relation.php) | Trait that provides common methods related to a relation route
    - [_search](src/Route/_search.php) | Trait that grants methods for search route
    - [_specific](src/Route/_specific.php) | Trait that provides most methods used for a specific route
    - [_specificNav](src/Route/_specificNav.php) | Trait that provides a method to make a specific siblings navigator
    - [_specificPrimary](src/Route/_specificPrimary.php) | Trait that provides most methods used for a specific route using a primary segment
    - [_specificRelation](src/Route/_specificRelation.php) | Trait that provides methods to make an enumSet input
    - [_tableRelation](src/Route/_tableRelation.php) | Trait that provides methods to make a table relation route, used by some inputs
- [Routes](src/Routes.php) | Extended class for a collection of many untriggered routes
- [Row](src/Row.php) | Extended class to represent an existing row within a table
    - [Email](src/Row/Email.php) | Class to deal with a row of the email table, contains the emailModels
    - [Lang](src/Row/Lang.php) | Class to work with a row of the lang table, contains the text and translations
    - [Log](src/Row/Log.php) | Class to represent a row of the log table, stores user activities
    - [LogCron](src/Row/LogCron.php) | Class to represent a row of the logCron table, stores cron jobs results
    - [LogEmail](src/Row/LogEmail.php) | Class to represent a row of the logEmail table, stores emails sent
    - [LogError](src/Row/LogError.php) | Class to represent a row of the logError table, stores error objects
    - [LogHttp](src/Row/LogHttp.php) | Class to represent a row of the logHttp table, stores bad HTTP requests
    - [LogSql](src/Row/LogSql.php) | Class to represent a row of the logSql table, stores sql queries
    - [QueueEmail](src/Row/QueueEmail.php) | Class to deal with a row of the queueEmail table, stores the email to send
    - [Redirection](src/Row/Redirection.php) | Class to work with a row of the redirection table
    - [Session](src/Row/Session.php) | Class to work with a row of the session table
    - [User](src/Row/User.php) | Class for a row of the user table
    - [_log](src/Row/_log.php) | Trait that adds log-related methods to a row
    - [_new](src/Row/_new.php) | Trait that grants access some methods which allows creating rows statically
    - [_queue](src/Row/_queue.php) | Trait that adds queuing-related methods to a row
- [Rows](src/Rows.php) | Extended class for a collection of many rows within a same table
- [RowsIndex](src/RowsIndex.php) | Extended class for a collection of many rows within different tables (keys are indexed)
- [Segment](src/Segment)
    - [_boolean](src/Segment/_boolean.php) | Trait that issues a method to deal with boolean route segment (1 or 0)
    - [_col](src/Segment/_col.php) | Trait to manage a route segment which must contain a column name or object
    - [_colRelation](src/Segment/_colRelation.php) | Trait to work with a route segment which must contain a column with a relation
    - [_cols](src/Segment/_cols.php) | Trait to manage a route segment which must contain many columns
    - [_direction](src/Segment/_direction.php) | Trait to deal with a route segment which must contain a sorting direction
    - [_filter](src/Segment/_filter.php) | Trait to manage a complex route segment which contains filtering directive
    - [_int](src/Segment/_int.php) | Trait that issues a method to deal with a simple integer route segment
    - [_limit](src/Segment/_limit.php) | Trait that issues a method to deal with a limit route segment (max per page)
    - [_order](src/Segment/_order.php) | Trait to manage a route segment which must contain an orderable column
    - [_orderColRelation](src/Segment/_orderColRelation.php) | Trait to work with a route segment which must contain an orderable column relation
    - [_orderTableRelation](src/Segment/_orderTableRelation.php) | Trait to manage a route segment which must contain an orderable table relation
    - [_page](src/Segment/_page.php) | Trait that issues a method to deal with a page route segment (page number)
    - [_pointer](src/Segment/_pointer.php) | Trait to work with a pointer route segment (value which contains a table and row)
    - [_primaries](src/Segment/_primaries.php) | Trait to deal with a route segment which must contain many rows
    - [_primary](src/Segment/_primary.php) | Trait to work with a route segment which must contain a row id or object
    - [_selected](src/Segment/_selected.php) | Trait that provides logic to deal with a route segment which represents a selected value
    - [_slug](src/Segment/_slug.php) | Trait that issues methods to work with a standard slug route segment
    - [_str](src/Segment/_str.php) | Trait that issues a method to deal with a simple string route segment
    - [_table](src/Segment/_table.php) | Trait to work with a route segment which must contain a table name or object
    - [_timestamp](src/Segment/_timestamp.php) | Trait to deal with a route segment which contains a timestamp
    - [_timestampMonth](src/Segment/_timestampMonth.php) | Trait to work with a route segment which contains the timestamp of a month
    - [_yes](src/Segment/_yes.php) | Trait that issues a method to deal with yes route segment (1)
- [Service](src/Service.php) | Extended abstract class that provides basic methods to manage a third-party service
    - [ClassUpload](src/Service/ClassUpload.php) | Class that provides methods to use verot/class.upload.php for resizing images
    - [JShrink](src/Service/JShrink.php) | Class that provides methods to use tedivm/jshrink for minifying JavaScript
    - [Ldap](src/Service/Ldap.php) | Class that grants some methods to connect to a ldap server
    - [PhpConcatenator](src/Service/PhpConcatenator.php) | Class used for concatenating a bunch of php files within a single one
    - [PhpMailer](src/Service/PhpMailer.php) | Class that provides methods to use phpmailer/phpmailer in order to send emails
    - [ScssPhp](src/Service/ScssPhp.php) | Class that grants methods to use scssphp/scssphp for compiling scss files
- [ServiceMailer](src/ServiceMailer.php) | Extended abstract class with basic methods that needs to be extended by a mailing service
- [ServiceRequest](src/ServiceRequest.php) | Extended abstract class with basic methods for a service that works with HTTP request
- [ServiceVideo](src/ServiceVideo.php) | Extended abstract class with methods for a service that provides a video object after an HTTP request
- [Services](src/Services.php) | Extended class for a collection containing many service objects
- [Session](src/Session.php) | Extended class that adds session support for user
- [Table](src/Table.php) | Extended class to represent an existing table within a database
- [Tables](src/Tables.php) | Extended class for a collection of many tables within a same database
- [Widget](src/Widget.php) | Extended abstract class that provides basic methods for a widget
    - [Calendar](src/Widget/Calendar.php) | Class that provides logic for the calendar widget
- [_access](src/_access.php) | Trait that provides methods to useful objects related to the Boot
- [_bootAccess](src/_bootAccess.php) | Trait that provides methods to access the Boot object
- [_dbAccess](src/_dbAccess.php) | Trait that provides a method to access the current database
- [_fullAccess](src/_fullAccess.php) | Trait that provides all access methods related to the Boot
- [_routeAttr](src/_routeAttr.php) | Trait that provides methods to work with routes in the attributes property
	
## Testing
**QuidPHP/Core** contains 33 test classes:
- [Boot](test/Boot.php) | Class for testing Quid\Core\Boot
- [Cell](test/Cell.php) | Class for testing Quid\Core\Cell
- [Cells](test/Cells.php) | Class for testing Quid\Core\Cells
- [Col](test/Col.php) | Class for testing Quid\Core\Col
- [Cols](test/Cols.php) | Class for testing Quid\Core\Cols
- [Com](test/Com.php) | Class for testing Quid\Core\Com
- [Db](test/Db.php) | Class for testing Quid\Core\Db
- [Error](test/Error.php) | Class for testing Quid\Core\Error
- [File](test/File.php) | Class for testing Quid\Core\File
- [Files](test/Files.php) | Class for testing Quid\Core\Files
- [Flash](test/Flash.php) | Class for testing Quid\Core\Flash
- [Lang](test/Lang.php) | Class for testing Quid\Core\Lang
- [Nav](test/Nav.php) | Class for testing Quid\Core\Nav
- [Redirection](test/Redirection.php) | Class for testing Quid\Core\Redirection
- [Request](test/Request.php) | Class for testing Quid\Core\Request
- [RequestHistory](test/RequestHistory.php) | Class for testing Quid\Core\RequestHistory
- [Response](test/Response.php) | Class for testing Quid\Core\Response
- [Role](test/Role.php) | Class for testing Quid\Core\Role
- [Roles](test/Roles.php) | Class for testing Quid\Core\Roles
- [Route](test/Route.php) | Class for testing Quid\Core\Route
- [Routes](test/Routes.php) | Class for testing Quid\Core\Routes
- [Row](test/Row.php) | Class for testing Quid\Core\Row
- [Rows](test/Rows.php) | Class for testing Quid\Core\Rows
- [RowsIndex](test/RowsIndex.php) | Class for testing Quid\Core\RowsIndex
- [Service](test/Service.php) | Class for testing Quid\Core\Service
- [ServiceMailer](test/ServiceMailer.php) | Class for testing Quid\Core\ServiceMailer
- [ServiceRequest](test/ServiceRequest.php) | Class for testing Quid\Core\ServiceRequest
- [ServiceVideo](test/ServiceVideo.php) | Class for testing Quid\Core\ServiceVideo
- [Services](test/Services.php) | Class for testing Quid\Core\Services
- [Session](test/Session.php) | Class for testing Quid\Core\Session
- [Table](test/Table.php) | Class for testing Quid\Core\Table
- [Tables](test/Tables.php) | Class for testing Quid\Core\Tables
- [Widget](test/Widget.php) | Class for testing Quid\Core\Widget

**QuidPHP/Core** testsuite can be run by creating a new [quidphp/project](https://github.com/quidphp/project).
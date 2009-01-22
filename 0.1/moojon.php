<?php 
require_once('classes/moojon.base.class.php');
require_once('classes/moojon.config.class.php');
require_once('classes/moojon.files.class.php');
require_once('classes/moojon.uri.class.php');
require_once('classes/moojon.request.class.php');
require_once('classes/moojon.base.app.class.php');
require_once('classes/moojon.base.controller.class.php');
require_once('classes/moojon.inflect.class.php');

require_once('classes/adapters/MySQL/moojon.connection.class.php');
require_once('classes/adapters/MySQL/moojon.query.utilities.class.php');
require_once('classes/adapters/MySQL/moojon.query.class.php');
require_once('classes/adapters/MySQL/moojon.query.builder.class.php');
require_once('classes/adapters/MySQL/moojon.query.runner.class.php');
require_once('classes/adapters/MySQL/moojon.join.class.php');
require_once('classes/adapters/MySQL/moojon.left.join.class.php');
require_once('classes/adapters/MySQL/moojon.right.join.class.php');
require_once('classes/adapters/MySQL/moojon.inner.join.class.php');
require_once('classes/adapters/MySQL/moojon.cross.join.class.php');
require_once('classes/adapters/MySQL/moojon.joins.class.php');

require_once('classes/adapters/MySQL/moojon.base.model.class.php');
require_once('classes/adapters/MySQL/moojon.model.properties.class.php');
require_once('classes/adapters/MySQL/moojon.model.collection.class.php');

require_once('classes/adapters/MySQL/moojon.base.relationship.class.php');
require_once('classes/adapters/MySQL/moojon.has.one.relationship.class.php');
require_once('classes/adapters/MySQL/moojon.has.many.relationship.class.php');
require_once('classes/adapters/MySQL/moojon.has.many.to.many.relationship.class.php');

require_once('classes/adapters/MySQL/columns/moojon.base.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.base.number.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.base.extended.number.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.bit.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.tinyint.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.smallint.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.mediumint.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.int.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.integer.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.bigint.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.real.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.double.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.float.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.decimal.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.numeric.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.base.string.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.base.extended.string.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.base.binary.string.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.varchar.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.binary.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.varbinary.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.date.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.time.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.datetime.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.timestamp.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.year.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.tinyblob.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.blob.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.mediumblob.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.longblob.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.tinytext.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.text.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.mediumtext.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.longtext.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.base.enum.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.enum.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.set.column.class.php');

moojon_files::require_directory_files(PROJECT_PATH.'/models/base/');
moojon_files::require_directory_files(PROJECT_PATH.'/models/');

$con = moojon_connection::init('localhost', 'bloodbowl', 'bloodbowl99', 'bloodbowl');

$app_name = moojon_uri::get_app();
$controller_name = moojon_uri::get_controller();

require_once(PROJECT_PATH."/apps/$app_name/$app_name.app.class.php");
require_once(PROJECT_PATH."/apps/$app_name/controllers/$controller_name.controller.class.php");
$app_class_name = moojon_uri::get_app().'_app';
$app = new $app_class_name;

$con->close();
?>
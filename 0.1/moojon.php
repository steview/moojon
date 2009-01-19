<?php 
//require_once('classes/moojon.request.class.php');
//$request = new moojon_request(require_once('config/moojon.config.php'));
include('classes/moojon.base.class.php');
include('classes/moojon.inflect.class.php');

include('classes/adapters/MySQL/moojon.connection.class.php');
include('classes/adapters/MySQL/moojon.query.utilities.class.php');
include('classes/adapters/MySQL/moojon.query.class.php');
include('classes/adapters/MySQL/moojon.query.builder.class.php');
include('classes/adapters/MySQL/moojon.query.runner.class.php');
include('classes/adapters/MySQL/moojon.join.class.php');
include('classes/adapters/MySQL/moojon.left.join.class.php');
include('classes/adapters/MySQL/moojon.right.join.class.php');
include('classes/adapters/MySQL/moojon.inner.join.class.php');
include('classes/adapters/MySQL/moojon.cross.join.class.php');
include('classes/adapters/MySQL/moojon.joins.class.php');

include('classes/adapters/MySQL/moojon.base.model.class.php');
include('classes/adapters/MySQL/moojon.model.properties.class.php');
include('classes/adapters/MySQL/moojon.model.collection.class.php');

include('classes/adapters/MySQL/moojon.base.relationship.class.php');
include('classes/adapters/MySQL/moojon.has.one.relationship.class.php');
include('classes/adapters/MySQL/moojon.has.many.relationship.class.php');
include('classes/adapters/MySQL/moojon.has.many.to.many.relationship.class.php');

include('classes/adapters/MySQL/columns/moojon.base.column.class.php');
include('classes/adapters/MySQL/columns/moojon.base.number.column.class.php');
include('classes/adapters/MySQL/columns/moojon.base.extended.number.column.class.php');
include('classes/adapters/MySQL/columns/moojon.bit.column.class.php');
include('classes/adapters/MySQL/columns/moojon.tinyint.column.class.php');
include('classes/adapters/MySQL/columns/moojon.smallint.column.class.php');
include('classes/adapters/MySQL/columns/moojon.mediumint.column.class.php');
include('classes/adapters/MySQL/columns/moojon.int.column.class.php');
include('classes/adapters/MySQL/columns/moojon.integer.column.class.php');
include('classes/adapters/MySQL/columns/moojon.bigint.column.class.php');
include('classes/adapters/MySQL/columns/moojon.real.column.class.php');
include('classes/adapters/MySQL/columns/moojon.double.column.class.php');
include('classes/adapters/MySQL/columns/moojon.float.column.class.php');
include('classes/adapters/MySQL/columns/moojon.decimal.column.class.php');
include('classes/adapters/MySQL/columns/moojon.numeric.column.class.php');
include('classes/adapters/MySQL/columns/moojon.base.string.column.class.php');
include('classes/adapters/MySQL/columns/moojon.base.extended.string.column.class.php');
include('classes/adapters/MySQL/columns/moojon.base.binary.string.column.class.php');
include('classes/adapters/MySQL/columns/moojon.varchar.column.class.php');
include('classes/adapters/MySQL/columns/moojon.binary.column.class.php');
include('classes/adapters/MySQL/columns/moojon.varbinary.column.class.php');
include('classes/adapters/MySQL/columns/moojon.date.column.class.php');
include('classes/adapters/MySQL/columns/moojon.time.column.class.php');
include('classes/adapters/MySQL/columns/moojon.datetime.column.class.php');
include('classes/adapters/MySQL/columns/moojon.timestamp.column.class.php');
include('classes/adapters/MySQL/columns/moojon.year.column.class.php');
include('classes/adapters/MySQL/columns/moojon.tinyblob.column.class.php');
include('classes/adapters/MySQL/columns/moojon.blob.column.class.php');
include('classes/adapters/MySQL/columns/moojon.mediumblob.column.class.php');
include('classes/adapters/MySQL/columns/moojon.longblob.column.class.php');
include('classes/adapters/MySQL/columns/moojon.tinytext.column.class.php');
include('classes/adapters/MySQL/columns/moojon.text.column.class.php');
include('classes/adapters/MySQL/columns/moojon.mediumtext.column.class.php');
include('classes/adapters/MySQL/columns/moojon.longtext.column.class.php');
include('classes/adapters/MySQL/columns/moojon.base.enum.column.class.php');
include('classes/adapters/MySQL/columns/moojon.enum.column.class.php');
include('classes/adapters/MySQL/columns/moojon.set.column.class.php');

include('models/base/base.coach.model.class.php');
include('models/base/base.injury.model.class.php');
include('models/base/base.injury_type.model.class.php');
include('models/base/base.player.model.class.php');
include('models/base/base.player_skill.model.class.php');
include('models/base/base.player_type.model.class.php');
include('models/base/base.player_type_skill_type.model.class.php');
include('models/base/base.skill.model.class.php');
include('models/base/base.skill_type.model.class.php');
include('models/base/base.team.model.class.php');
include('models/coach.model.class.php');
include('models/injury.model.class.php');
include('models/injury_type.model.class.php');
include('models/player.model.class.php');
include('models/player_skill.model.class.php');
include('models/player_type.model.class.php');
include('models/player_type_skill_type.model.class.php');
include('models/skill.model.class.php');
include('models/skill_type.model.class.php');
include('models/team.model.class.php');


?>
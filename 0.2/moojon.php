<?php
require_once(MOOJON_PATH.'/classes/moojon.base.class.php');
require_once(MOOJON_PATH.'/classes/moojon.exception.class.php');
require_once(MOOJON_PATH.'/classes/moojon.config.class.php');
require_once(MOOJON_PATH.'/classes/moojon.paths.class.php');
require_once(MOOJON_PATH.'/classes/moojon.base.cli.class.php');
require_once(MOOJON_PATH.'/classes/moojon.cli.class.php');
require_once(MOOJON_PATH.'/classes/moojon.runner.class.php');
require_once(MOOJON_PATH.'/functions/moojon.core.functions.php');
moojon_runner::run();
?>
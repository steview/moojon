<?php
require_once(MOOJON_PATH.'/classes/moojon.base.class.php');
require_once(MOOJON_PATH.'/classes/moojon.config.class.php');
require_once(MOOJON_PATH.'/classes/moojon.paths.class.php');
require_once(MOOJON_PATH.'/functions/moojon.core.functions.php');
switch (strtoupper(UI)) {
	case 'CGI':
		require_once(moojon_paths::get_app_path(moojon_uri::get_app()));
		$app_class = moojon_uri::get_app().'_app';
		$app = new $app_class;
		break;
	case 'CLI':
		new $cli();
		break;
	default:
		moojon_base::handle_error('Invalid UI ('.UI.')');
		break;
}
?>
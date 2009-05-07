<?php
return array(
	'default_app' => 'client',
	'default_controller' => 'index',
	'default_action' => 'index',
	'apps_directory' => 'apps',
	'controllers_directory' => 'controllers',
	'shared_directory' => 'shared',
	'views_directory' => 'views',
	'layouts_directory' => 'layouts',
	'models_directory' => 'models',
	'helpers_directory' => 'helpers',
	'partials_directory' => 'partials',
	'base_models_directory' => 'base',
	'migrations_directory' => 'migrations',
	'public_directory' => 'public',
	'images_directory' => 'images',
	'css_directory' => 'css',
	'js_directory' => 'js',
	'script_directory' => 'script',
	'config_directory' => 'config',
	'library_directory' => 'library',
	'vendor_directory' => 'vendor',
	'default_helpers' => 'ui, url',
	'default_js' => 'jquery, jquery.validate, project',
	'default_css' => 'core, form, layout',
	'index_file' => '/index.php/',
	'cookie_expiry' => 1209600,
	'security_login_condition_string' => "%s = '%s' AND %s = '%s'",
	'security_check_condition_string' => "%s = '%d'",
	'security_token_key' => 'security_token',
	'security_key' => 'security',
	'security_identity_key' => 'email',
	'security_password_key' => 'password',
	'security_remember_key' => 'remember',
	'security_identity_label' => 'Email:',
	'security_password_label' => 'Password:',
	'security_remember_label' => 'Remember me on this computer for two weeks',
	'security_failure_message' => 'Invalid %s / %s combination. Login failure.',
	'security_class' => 'moojon_security',
	'security_model' => 'coach',
	'security_controller' => 'moojon_security',
	'security_action' => 'login',
);
?>
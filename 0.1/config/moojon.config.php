<?php
return array(
	'scheme' => 'http',
	'charset' => 'UTF-8',
	'mail_subject' => 'No subject',
	'mail_from_email' => 'fake@mail.com',
	'mail_from_name' => 'Anonymous',
	'default_app' => 'client',
	'default_controller' => 'index',
	'default_action' => 'index',
	'adapter' => null,
	'db_host' => null,
	'db_username' => null,
	'db_password' => null,
	'db' => null,
	'classes_directory' => 'classes',
	'adapters_directory' => 'adapters',
	'columns_directory' => 'columns',
	'validations_directory' => 'validations',
	'tags_directory' => 'tags',
	'tag_attributes_directory' => 'attributes',
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
	'library_directory' => 'library',
	'vendor_directory' => 'vendor',
	'default_helpers' => 'ui, url',
	'default_js' => 'jquery, jquery.validate, project',
	'default_css' => 'core, form, layout',
	'index_file' => '/index.php/',
	'flash_key' => 'flash',
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
	'security_remember_label' => 'Remember login for two weeks',
	'security_failure_message' => 'Invalid %s / %s combination. Login failure.',
	'security_class' => 'moojon_security',
	'security_model' => 'coach',
	'security_app' => 'moojon_app',
	'security_controller' => 'moojon_security',
	'security_action' => 'login',
	'exception_handler_class' => 'moojon_exception_handler',
	'exception_app' => 'moojon_app',
	'exception_controller' => 'moojon_exception',
	'exception_action' => 'index',
);
?>
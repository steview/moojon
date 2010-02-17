<?php
return array(
	'timezone' => 'Europe/London',
	'put_path' => 'php://input',
	'scheme' => 'http',
	'charset' => 'UTF-8',
	'mail_subject' => 'No subject',
	'from_mail_email' => 'fake@mail.com',
	'from_mail_name' => 'Anonymous',
	'default_app' => 'client',
	'default_controller' => 'index',
	'default_action' => 'index',
	'db_driver' => null,
	'db_host' => null,
	'db_port' => null,
	'db_unix_socket' => null,
	'db_dns' => null,
	'db_name' => null,
	'db_username' => null,
	'db_password' => null,
	'cache_for' => 0,
	'classes_directory' => 'classes',
	'interfaces_directory' => 'interfaces',
	'db_drivers_directory' => 'db_drivers',
	'columns_directory' => 'columns',
	'validations_directory' => 'validations',
	'tags_directory' => 'tags',
	'tag_attributes_directory' => 'attributes',
	'apps_directory' => 'apps',
	'controllers_directory' => 'controllers',
	'views_directory' => 'views',
	'layouts_directory' => 'layouts',
	'templates_directory' => 'templates',
	'library_directory' => 'library',
	'pluggins_directory' => 'pluggins',
	'models_directory' => 'models',
	'cache_directory' => 'cache',
	'helpers_directory' => 'helpers',
	'partials_directory' => 'partials',
	'base_models_directory' => 'base',
	'migrations_directory' => 'migrations',
	'public_directory' => 'public',
	'images_directory' => 'images',
	'css_directory' => 'css',
	'js_directory' => 'js',
	'uploads_directory' => 'uploads',
	'scaffolds_directory' => 'scaffolds',
	'script_directory' => 'script',
	'default_image_ext' => 'png',
	'default_helpers' => 'tag, model.tag, rest',
	'default_js' => 'jquery, jquery.validate, project',
	'default_css' => 'core, form, layout',
	'index_file' => '/index.php/',
	'flash_key' => 'flash',
	'method_key' => '_method',
	'redirection_key' => '_redirection',
	'cookie_expiry' => 1209600,
	'security_token_key' => 'security_token',
	'security_key' => 'security',
	'security_identity_key' => 'email',
	'security_password_key' => 'password',
	'security_remember_key' => 'remember',
	'security_identity_label' => 'Email:',
	'security_password_label' => 'Password:',
	'security_remember_label' => 'Remember login for two weeks',
	'security_login_failure_message' => 'Login failure',
	'security_login_message' => 'You have been logged in',
	'security_please_login_message' => 'Please login',
	'security_logout_message' => 'You have been logged in',
	'security_class' => 'moojon_security',
	'security_model' => 'user',
	'security_app' => 'moojon',
	'security_controller' => 'security',
	'security_action' => 'login',
	'exception_handler_class' => 'moojon_exception_handler',
	'exception_app' => 'moojon',
	'exception_controller' => 'exception',
	'exception_action' => 'index',
	'date_format' => 'Y/m/d',
	'datetime_format' => 'Y/m/d H:i:s',
	'time_format' => 'H:i:s',
	'start_year' => 1900,
	'end_year' => 2100,
	'validation_error_message' => 'Some errors occurred. Please correct the errors and re-submit:',
	'no_records_message' => 'No records.',
	'confirm_deletion_message' => 'Please confirm deletion of record.',
	'mime_type_column' => 'mime_type',
	'mime_type_database_path' => '/usr/share/misc/magic',
	'paginator_page_symbol_name' => 'page',
	'paginator_limit_symbol_name' => 'limit',
	'paginator_all_symbol_name' => 'all',
	'paginator_limit' => 10,
	'paginator_max_items' => 11,
);
?>
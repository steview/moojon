<?php
final class moojon_exception_handler extends moojon_base_exception_handler {
	protected function run(moojon_exception $exception) {
		switch (UI) {
			case 'CGI':
				$app =  moojon_runner::app_from_uri(moojon_config::get('exception_app').'/'.moojon_config::get('exception_controller').'/'.moojon_config::get('exception_action'));
				if (ENVIRONMENT != 'development' && moojon_config::get('exception_mail')) {
					$mailer = new moojon_mailer;
					$mailer->set_from(moojon_config::get('from_mail_email'), moojon_config::get('from_mail_name'));
					$mailer->set_to(moojon_config::get('webmaster_mail_email'), moojon_config::get('webmaster_mail_name'));
					$mailer->set_subject(moojon_config::get('exception_mail_subject'));
					//$mailer->set_html($exception->getMessage().' '.$exception->getFile().' '.$exception->getLine());
					$mailer->send();
				}
				echo $app->render();
				break;
			case 'CLI':
				echo $this->exception;
				break;
		}
	}
}
?>
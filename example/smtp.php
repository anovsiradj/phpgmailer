<?php
require __DIR__ . '/boot.php';

$mailer = new anovsiradj\PHPGMailer\PHPGMailer;

$mailer->isSMTP(
	getenv('GMAIL_SMTP_USERNAME'),
	getenv('GMAIL_SMTP_PASSWORD'),
);

$mailer->mailFrom(getenv('EMAIL_SENDER'))->mailTo(getenv('EMAIL_RECEIVER'));

$mailer->contents(
	'From LOCALHOST with Love',
	'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
);

$mailer->send();

dump(
	$mailer->pm_last_send,
	$mailer->pm_last_logs,
);

<?php

namespace anovsiradj\PHPGMailer;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\SMTP;

class PHPGMailer
{
	public PHPMailer $pm;
	public OAuth $pmoa;

	public bool $pm_last_send;
	public string $pm_last_logs;

	public array $pmoa_options = [
		'provider' => 'Google',
	];

	public function __construct()
	{
		$this->load_pm(null);
	}

	public function load_pm(?PHPMailer $pm)
	{
		if (empty($pm)) {
			$this->pm = new PHPMailer;
		} else {
			$this->pm = $pm;
		}

		/* supaya message tidak terpotong. jika tidak isHTML(true),
		"message" yang dikirim hanya sampai baris pertama saja. */
		$this->pm->isHTML(true);
	}

	public function load_pmoa(?OAuth $pmoa, array $options = [])
	{
		if (empty($pmoa)) {
			$this->pmoa = new OAuth($options);
		}
	}

	public function isSMTP(string $Username, string $Password)
	{
		$this->pm->isSMTP();

		$this->pm->SMTPDebug = SMTP::DEBUG_SERVER;
		$this->pm->SMTPAuth = true;
		$this->pm->Host = 'smtp.gmail.com';

		$this->pm->Username = $Username;
		$this->pm->Password = $Password;

		return $this->SMTPTLS();
	}

	/**
	* using SMTP with SSL security connection
	*/
	public function SMTPSSL()
	{
		$this->pm->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		$this->pm->Port = 465;
		return $this;
	}

	/**
	* using SMTP with TLS security connection (default)
	*/
	public function SMTPTLS()
	{
		$this->pm->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$this->pm->Port = 587;
		return $this;
	}

	// todo //
	public function isXOAUTH2(string $client_id, string $client_secret)
	{
		throw new \Exception("Currently Not Yet Implemented", 1);
		return $this;
	}

	public function mailFrom(string $mail, ?string $name = null)
	{
		$this->pm->setFrom($mail, $name);
		return $this;
	}

	/**
	 * @param string|array $mail
	 * @throws TypeError
	 */
	public function mailTo($mail, ?string $name = null)
	{
		$receiver = [];
		if (is_string($mail)) {
			if (empty($name)) {
				$receiver[] = $mail;
			} else {
				$receiver[$mail] = $name;
			}
		} elseif (is_array($mail)) {
			$receiver = $mail;
		} else {
			throw new \TypeError("Wrong Variable Type", 1);
		}
		foreach ($receiver as $mail => $name) {
			if (is_int($mail)) {
				$mail = $name;
				$name = null;
			}
			$this->pm->addAddress($mail, $name);
		}
		return $this;
	}

	public function contents(?string $Subject, ?string $message)
	{
		$this->pm->Subject = $Subject;
		$this->pm->Body = $message;

		return $this;
	}

	public function send(): bool
	{
		try {
			ob_start();
			$r = $this->pm_last_send = $this->pm->send();
			$this->pm_last_logs = ob_get_contents();
			if (ob_get_level() === 1) ob_end_clean();
		} catch (\Exception $e) {
			$r = false;
			$this->pm_last_logs = $this->pm->ErrorInfo;
		}
		return $r;
	}
}

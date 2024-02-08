<?php

class Help extends Bot 
{
	public function __construct($conf, $chat_id)
	{
		parent::__construct($conf, $chat_id);
	}

	public function help()
	{
		$message = "<b>General Help</b>" . chr(10) . chr(10);
		$message .= "<code>/help mikrotik</code>" . chr(10) . "  - semua perintah mikrotik";

		return $this->send($message);
	}

	public function mikrotik()
	{
		$message = "<b>Mikrotik list perintah</b> <code>/help mikrotik</code>" . chr(10)."----------".chr(10);
		$message .= "<code>/mikrotik</code>" . chr(10) . "  - Melihat mikrotik terhubung" . chr(10) . chr(10);
		$message .= "<code>/mikrotik hotspot</code>" . chr(10) . "  - Melihat semua user hotspot yang aktive" . chr(10) . chr(10);
		$message .= "<code>/mikrotik hotspot_host</code>" . chr(10) . "  - Melihat perangkat terhubung (host)" . chr(10) . chr(10);

		return $this->send($message);
	}
}
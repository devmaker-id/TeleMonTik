<?php

class Bot
{
	private $api_url = '';
	private $only_trusted = true;
	private $trusted = array();
	private $chat_id = '';

	public function __construct($conf, $chat_id)
	{
		$this->api_url = 'https://api.telegram.org/bot' . $conf['bot_token'];
		$this->only_trusted = $conf['only_trusted'];
		$this->trusted = $conf['trusted'];
		$this->chat_id = $chat_id;
	}

	public function isTrusted()
	{
		if (!$this->only_trusted) {
			return true;
		}

		if (in_array($this->chat_id, $this->trusted)) {
			return true;
		}

		return false;
	}

	public function log($message)
	{
		error_log(date("Y-m-d H:i:s") . " - " . $message . "\n", 3, APPPATH.'Controllers/Telegram/log.log');
	}

	public function send($message)
	{
		$text = trim($message);

		if (strlen(trim($text)) > 0) {
			$send = $this->api_url . "/sendmessage?parse_mode=html&chat_id=" . $this->chat_id . "&text=" . urlencode($text);
			file_get_contents($send);
			return true;
		}

		return false;
	}

	public function unauthorized()
	{
	  $message = "Silahkan Buka link".chr(10);
	  $message .= base_url($this->chat_id).chr(10);
	  $message .= "tambahkan mikrotik anda, ingat satu id telegram untuk satu mikrotik.".chr(10);
	  $message .= "dan mohon teliti saat lengkapi data yang diminta".chr(10);
	    
		return $this->send($message);
	}

	public function unknown()
	{
	  $message = "Panduan chek ".base_url().chr(10);
	  $message .= "atau ketuk commend /help";
		return $this->send($message);
	}
}
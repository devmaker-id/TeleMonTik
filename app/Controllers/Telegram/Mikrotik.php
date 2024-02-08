<?php
use App\Controllers\RouterosAPI;

class Mikrotik extends Bot
{
	public function __construct($conf, $chat_id)
	{
		parent::__construct($conf, $chat_id);
		$this->chat_id = $chat_id;
	}
	
	//atributvpengecekan id telegram join mikrotik//
	public function LoginMikrotik($id)
  {
    $path = APPPATH."MasterData/mikrotik.json";
    $data = file_get_contents($path, true);
    $result = json_decode($data, true);
    $client = [];
    for($i=0;$i<count($result);$i++){
      if($result[$i]["telegram_id"] == "$id"){
        $client = $result[$i];
      }
    }
    return $client;
  }
  //finis atribut//
	
	public function mikrotik()
	{
	  $mk = $this->LoginMikrotik($this->chat_id);
	  if($mk)
	  {
	    $api = new RouterosAPI();
      $api->connect($mk["host_api"],$mk["username"],$mk["password"]);
      $sys = $api->comm("/system/resource/print");
      $identy = $api->comm("/system/identity/print");
      $api->disconnect();
      
      $message = "<b>Mikrotik Tersambung - ".$identy[0]["name"]."</b>" . chr(10);
  		$message .= "Mikrotik : ".$sys[0]["board-name"]. chr(10);
  		$message .= "Cpu : ".$sys[0]["cpu"]. chr(10);
  		$message .= "Cpu Load : ".$sys[0]["cpu-load"]."%". chr(10);
  		$message .= "Os Versi : ".$sys[0]["version"]. chr(10);
  		
  	  return $this->send($message);
	  } else {
	    $message = "Silahkan Buka link".chr(10);
	    $message .= base_url($this->chat_id).chr(10);
	    $message .= "tambahkan mikrotik anda, ingat satu id telegram untuk satu mikrotik.".chr(10);
	    $message .= "dan mohon teliti saat lengkapi data yang diminta".chr(10);
	    
		  return $this->send($message);
	  }
	}

	public function hotspot()
	{
	  $mk = $this->LoginMikrotik($this->chat_id);
	  if($mk)
	  {
	    $api = new RouterosAPI();
      $api->connect($mk["host_api"],$mk["username"],$mk["password"]);
      $data = $api->comm("/ip/hotspot/active/print");
      $api->disconnect();
      
      $message = "user sedang online : ".count($data)." Perangkat";
      
  		return $this->send($message);
	  } else {
	    $message = "Anda belum mempunyai mikrotik terhubung";
	    $message .= chr(10)."ketik /mikrotik";
		  return $this->send($message);
	  }
	}

	public function hotspot_host()
	{
	  $mk = $this->LoginMikrotik($this->chat_id);
	  if($mk)
	  {
	    $api = new RouterosAPI();
      $api->connect($mk["host_api"],$mk["username"],$mk["password"]);
      $host = $api->comm("/ip/hotspot/host/print");
      $api->disconnect();
      
      $message = "Terhubung Host : ".count($host);
      $message .= " Perangkat";
	    return $this->send($message);
	  } else {
		  $message = "Anda belum mempunyai mikrotik terhubung";
	    $message .= chr(10)."ketik /mikrotik";
		  return $this->send($message);
	  }
	  
	}


}
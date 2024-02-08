<?php

class Bibit extends Bot 
{
	public function __construct($conf, $chat_id)
	{
		parent::__construct($conf, $chat_id);
		$this->chat_id = $chat_id;
	}

	public function bibit()
	{
		$message = "Siap bos! Bibit ready.";
		$message .= "\n/help - informasi perintah";

		return $this->send($message);
	}
	
	public function info()
	{
	  $jsontmp = file_get_contents("config/telegram/".$this->chat_id.".json");
	  $datatmp = json_decode($jsontmp);
	  $json = file_get_contents("config/telegram/join/".$datatmp->private_key.".json");
	  $data = json_decode($json);
	  
	  $message = "Siap komandan, berikut detailnya\n";
	  $message .= "Nama server : {$data->server_name} \n";
	  $message .= "Private Key : <code>{$data->private_key}</code> \n";
	  $message .= "Type server : {$data->server} \n";
	  $message .= "Bergabung : {$data->created_at} \n";
	  $message .= "\nPenting!!\npergunakan private key untuk singkronisasi mikrotik anda ke server kami, simpan dengan baik, jika lupa silahkan ketik kembali /info\n\n";
	  $message .= "\nApakah ada lagi, bos Q!!";
	  
	  return $this->send($message);
	}
	
	public function mikrotik()
	{
	  $jsontmp = file_get_contents("config/telegram/".$this->chat_id.".json");
	  $datatmp = json_decode($jsontmp);
	  $json = file_get_contents("config/telegram/join/".$datatmp->private_key.".json");
	  $data = json_decode($json);
	  
	  
	  
	  $priveJson = file_get_contents("config/mikrotik/join/".$data->private_key.".json");
	  $mikrotik = json_decode($priveJson);
	  
	  if($mikrotik){
	     $message = "Berikut <b>Mikrotik Terhubung</b>\n";
	     $no =1;
	     for ($i=0; $i<count($mikrotik); $i++){
	        $message .= $no++." <b>Mikrotik ".$mikrotik[$i]->devices."</b>\n";
	        $message .= " - RoS version {$mikrotik[$i]->version}\n";
	     }
	  } else {
	     $message = "Tidak ada mikrotik terhubung bos!,\nTempel kode ini di setiap mikrotik\n\n";
	     $message .= '<code>:local softwareid [/system license get software-id];
:local devices [/system resource get board-name];
:local ver [/system resource get version];
:local name [/system identity get name];
:local private "'.$data->private_key.'";

:do {
/tool fetch http-method=post keep-result=no http-header-field="Content-Type: application/json" http-data="{\"private_key\":\"$private\",\"software_id\":\"$softwareid\",\"devices\":\"$devices\",\"version\":\"$ver\",\"name\":\"$name\"}" url="https://sv01.bibitnet.web.id/mikrotikhook.php";
} on-error={ log warning "Greeter: Send to server Failed!" }</code>';
	     $message .= "\n\nTinggal ketuk scrip di atas bos Q";
	  }
	  
	  return $this->send($message);
	}

}
<?php
namespace App\Models;
use CodeIgniter\Model;
class UserModel extends Model
{
  private $chat_id = '';
  private $idtrusted = array();
  
  public function __construct($chat_id)
  {
    //load data koneksi ke mikrotik
    $json = file_get_contents(APPPATH."MasterData/mikrotik.json");
    $result = json_decode($json);
    $client = [];
    for($i=0;$i<count($result);$i++)
    {
      if($result[$i]["telegram_id"] == $chat_id)
      {
        $client = $result[$i];
      }
    }
    if ($client)
    {
      
    }
    
  }
    
}
<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Hotspot extends ResourceController
{
  use ResponseTrait;
  public function LoginMikrotik($key,$val)
  {
    $path = APPPATH."MasterData/mikrotik.json";
    $data = file_get_contents($path, true);
    $result = json_decode($data, true);
    $client = [];
    for($i=0;$i<count($result);$i++){
      if($result[$i][$key] == "$val"){
        $client = $result[$i];
      }
    }
    return $client;
  }
  
  public function index()
  {
    $id = $this->request->getGet("key");
    $login = $this->LoginMikrotik("telegram_id", $id);
    if($login){
      $api = new RouterosAPI();
      $api->connect($login["host_api"],$login["username"],$login["password"]);
      $data = $api->comm("/ip/hotspot/active/print");
      $api->disconnect();
      
      $result = [
        "error" => false,
        "jumlah" => count($data),
        "data" => $data
      ];
    
    } else {
      $result = [
        "error"=>true,
        "info"=>"id telegram tidak terdaftar"
      ];
    }
    return $this->respond($result);
  }
  //create new item single
  public function create()
  {
    $mikrotik = $this->request->getVar('sn');
    $login = $this->LoginMikrotik("software_id", $mikrotik);
    if($login){
      $path = APPPATH."MasterData/".$mikrotik."/login_hotspot.json";
      if(file_exists($path) == false){
        mkdir(APPPATH."MasterData/".$mikrotik);
        file_put_contents($path, json_encode([]));
      }
      
      $data = [
        'sn'=>$mikrotik,
        'date'=>$this->request->getVar('date'),
        'device'=>$this->request->getVar('device'),
        'user'=>$this->request->getVar('user'),
        'address'=>$this->request->getVar('address'),
        'mac'=>$this->request->getVar('mac'),
        'interval'=>$this->request->getVar('interval'),
        'profile'=>$this->request->getVar('profile'),
        'comment'=>$this->request->getVar('comment'),
      ];
      
      $json = json_decode(file_get_contents($path), true);
      array_push($json, $data);
      file_put_contents($path, json_encode($json));
      
      $response = [
        'status'   => 201,
        'error'    => null,
        'messages' => [
          'success' => 'Data user berhasil ditambahkan.'
        ]
      ];
    } else {
      $response = [
        'error'    => true,
        'messages' => 'Mikrotik Tidak Terdaftar.'
      ];
    }
    return $this->respondCreated($response);
  }
    // single user
  public function show($user = null)
  {
    $id = $this->request->getVar("key");
    $login = $this->LoginMikrotik("telegram_id", $id);
    if($login){
      $api = new RouterosAPI();
      $api->connect($login["host_api"],$login["username"],$login["password"]);
      $data = $api->comm("/ip/hotspot/active/print", array(
        "?user" => "$user"
      ));
      $api->disconnect();
      
      if ($data) {
        return $this->respond($data);
      } else {
        return $this->failNotFound('Data tidak ditemukan.');
      }
    } else {
      $response = [
        'error'    => true,
        'messages' => 'Mikrotik Tidak Terdaftar.'
      ];
      return $this->respond($response);
    }
  }
    
    
}

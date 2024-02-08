<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Hotspotprofile extends ResourceController
{
  use ResponseTrait;
  public function index()
  {
    $api = new RouterosAPI();
    $api->connect("","","");
    $data = $api->comm("/ip/hotspot/user/profile/print");
    $api->disconnect();
    
    $profile = [];
    for($i=0; $i<count($data); $i++){
      $speed = isset($data[$i]["rate-limit"]);
      $salt = [
        "name" => $data[$i]["name"],
        "share" => $data[$i]["shared-users"],
        "speed" => ($speed ? $data[$i]["rate-limit"] : "unlimited"),
      ];
      array_push($profile, $salt);
    }
    
    return $this->respond($profile);
  }
  //create new item single
  public function create()
  {
    $mikrotik = $this->request->getVar('sn');
    
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
    return $this->respondCreated($response);
  }
    // single user
  public function show($user = null)
  {
    //$model = new ProductModel();
    //$data = $model->where('id', $id)->first();
    $api = new RouterosAPI();
    $api->connect("","","");
    $data = $api->comm("/ip/hotspot/active/print", array(
      "?user" => "$user"
    ));
    $api->disconnect();
    if ($data) {
      return $this->respond($data);
    } else {
      return $this->failNotFound('Data tidak ditemukan.');
    }
  }
    
    
}

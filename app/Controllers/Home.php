<?php
namespace App\Controllers;
use App\Controllers\RouterosAPI;

class Home extends BaseController
{
  public function __construct()
  {
    $this->telebotlink = "https://t.me/telemontikbot";
  }
  
  public function chek_mikrotik()
  {
    if($this->request->isAJAX())
    {
      $input = $this->request->getVar();
      if($input["teleid"]){
        if($input["host"]){
          if($input["username"]){
            if($input["password"]){
              $api = new RouterosAPI();
              $mikrotik = $api->connect($input["host"],$input["username"],$input["password"]);
              $api->disconnect();
              if($mikrotik){
                
                $json = file_get_contents(APPPATH."MasterData/mikrotik.json");
                $row = json_decode($json, true);
                $register = [];
                for($i=0;$i<count($row);$i++){
                  if($input["teleid"] == $row[$i]["telegram_id"]){
                    $register = true;
                  }
                }
                if($register){
                  $result = [
                    "error"=>true,
                    "color"=>"text-warning",
                    "info"=>"Mikrotik berhasil terhubung, namun id telegram sudah dimiliki mikrotik lain, gantilah id telegram"
                  ];
                } else {
                  $result = [
                    "success"=>true,
                    "btnKirim"=>"<hr><button class='btn btn-success btn-sm' id='btnkirim'>Simpan Mikrotik</button>",
                    "color"=>"text-success",
                    "btn"=>true,
                    "info"=>"Mikrotik berhasil dihubungi, silahkan klik Simpan"
                  ];
                }
                
                
              } else {
                $result = [
                  "error"=>true,
                  "color"=>"text-danger",
                  "btn"=>true,
                  "info"=>"Mikrotik Tidak bisa di hubungi, chek [host, username, password]"
                ];
              }
              
            } else {
              $result = [
                "error"=>true,
                "color"=>"text-danger",
                "info"=>"form password kosong bos...."
              ];
            }
            
          } else {
            $result = [
              "error"=>true,
              "color"=>"text-danger",
              "info"=>"form username kosong bos...."
            ];
          }
          
        } else {
          $result = [
            "error"=>true,
            "color"=>"text-danger",
            "info"=>"form host mikrotik kosong bos...."
          ];
        }
        
      } else {
        $result = [
          "error"=>true,
          "color"=>"text-danger",
          "info"=>"form telegram kosong bos...."
        ];
      }
      
      echo json_encode($result);
    } else {
      return false;
    }
  }
  
  public function save_mikrotik()
  {
    $input = $this->request->getPost();
    //dd($input);
    $mikrotik =[];
    $api = new RouterosAPI();
    $openmk = $api->connect($input["hostmikrotik"],$input["username"],$input["password"]);
    if($openmk){
      $r1 = $api->comm("/system/identity/print");
      $r2 = $api->comm("/system/license/print");
      $mikrotik = [
        $r1[0]["name"],
        $r2[0]["software-id"]
      ];
    }
    $api->disconnect();
    
    if($mikrotik){
      $pathmk = APPPATH."MasterData/mikrotik.json";
      $pathtrusted = APPPATH."MasterData/id_telegram_trusted.json";
      
      $mkjson = file_get_contents($pathmk);
      $allMikrotik = json_decode($mkjson,true);
      $mikrotikNew = [
        "telegram_id"=>$input["teleid"],
        "software_id"=>$mikrotik[1],
        "identity"=>$mikrotik[0],
        "host_api"=>$input["hostmikrotik"],
        "username"=>$input["username"],
        "password"=>$input["password"],
        "created_at"=>date("Y-m-d H:i:s"),
      ];
      array_push($allMikrotik, $mikrotikNew);
      file_put_contents($pathmk, json_encode($allMikrotik));
      
      $teleidjson = file_get_contents($pathtrusted);
      $allIdTelegram = json_decode($teleidjson,true);
      $newIdTrusted = $input["teleid"];
      array_push($allIdTelegram, $newIdTrusted);
      file_put_contents($pathtrusted, json_encode($allIdTelegram));
      
      $result = $this->telebotlink;
    }else{
      //$session = session();
      //$session->setFlashdata("error", "Periksa inputan form mikrotik kembali");
      $result = base_url($input["teleid"]);
    }
    return redirect()->to($result);
  }
  
  public function index($teleid=null)
  {
    $data = [
      "telegram_id"=>$teleid,
    ];
    
    return view('register/index', $data);
  }
  
  public function about()
  {
    header('Content-Type: application/json');
    $data = [
      "error" => false,
      "app_version"=>"1",
      "fitur_app"=>[
        "mikrotik terhubung - /mikrotik",
        "chek online hotspot active - /mikrotik hotspot",
        "chek online host - /mikrotik hotspot_host"
      ],
      "data" => [
        "created_at" => "feb/05/2024 00:00:00 WIB",
        "telegram_master" => $this->telebotlink,
        "message" => "Aplikasi ini di ciptakan Devmaker, untuk kebutuhan monitoring mikrotik",
        "environment"=>[
          "info"=>"folder akar env/.env",
          "opsi"=>[
            "development"=>"untuk pengembangan",
            "production"=>"untuk live"
          ],
        ],
        "update"=>[],
      ],
      "pustaka"=>[
        "samsung a32",
        "app acode",
        "app termux",
        "app telegram",
        "composer",
        "codeigniter 4",
        "bootstrap",
        "php8",
        "data save JSON only"
      ],
      "resource"=>[
        "master_data"=>"https://github.com/devmaker-id/",
        "routerosapi"=>"https://github.com/BenMenking/routeros-api"
      ],
      "panduan"=>[
        "1. Buka link telegram_master",
        "2. Pastikan anda mempunyai mikrotik",
        "3. Mikrotik bisa di aksess public",
        "4. Jika menggunakan remote vpn dari tunnel.my.id, akan memerlukan waktu 1×24Jam karena harus open port dahulu",
        "5. Secangkir kopi",
        "6. Sebatang udud"
      ],
      "sawer"=>[
        "doge"=>"DPSXjULpLSuF79oc39DgovkDDNKyoddZ5B"
      ],
    ];
    
    $result = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    echo "<pre>";
    print_r($result);
    echo "</pre>";
  }
}

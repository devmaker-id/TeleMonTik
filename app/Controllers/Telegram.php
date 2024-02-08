<?php
namespace App\Controllers;

class Telegram extends BaseController
{
  public function index()
  {
    //debug
    //https://4de2-112-215-224-143.ngrok-free.app
    //https://api.telegram.org/bot6741575196:AAFF7IUsY9MQ_Osxohm0YVfprg5OlBriWNY/setWebhook?url=https://4de2-112-215-224-143.ngrok-free.app/webhooktelegram
    
    //set webhook
    //https://api.telegram.org/bot6741575196:AAFF7IUsY9MQ_Osxohm0YVfprg5OlBriWNY/setWebhook?url=https://telegram.bibitnet.web.id/webhooktelegram
    //update
    //https://api.telegram.org/bot6741575196:AAFF7IUsY9MQ_Osxohm0YVfprg5OlBriWNY/getupdates
    
    $idTrusted = file_get_contents(APPPATH.'MasterData/id_telegram_trusted.json');
    $conf = [
      'bot_token' => '6741575196:AAFF7IUsY9MQ_Osxohm0YVfprg5OlBriWNY',
      'only_trusted' => TRUE,
      'trusted' => json_decode($idTrusted)
    ];

    spl_autoload_register(function($class) {
    	include_once APPPATH.'Controllers/Telegram/' . $class . '.php';
    });

    $content = file_get_contents("php://input");
    $update = json_decode($content, true);
    $chat_id = $update["message"]["chat"]["id"];
    $message = $update["message"]["text"];
    
    // Available bot commands
    $commands = [
    	'help',
    
    	// Server Commands
    	'server',
    
    	//Alias for /server uptime
    	'uptime',
    	
    	// Alias for /server uname
    	'uname',
    
    	// Alias for /server who
    	'who',
    
    	// Alias for /server disk
    	'disk',
    	
    	//Bibit Commands
    	'bibit',
    	
    	//aliases for /bibit info
    	'info',
    	'mikrotik',
    
    ];
    
    $arguments = [
    	// Mikrotik
    	'mikrotik'=>[
    	  'hotspot',
    	  'hotspot_host'
    	 ],
    	'server'=>[
    		'uptime',
    		'uname',
    		'who',
    		'disk',
    	],
    	'help'=>[
    		'mikrotik',
    	],
    	// Bibit
    	'bibit'=>[
    	  'info',
    	  'mikrotik_list',
    	],
    ];
    
    // Aliases for commands
    $alias = [
    	'uptime'=>'server',
    	'uname'=>'server',
    	'who'=>'server',
    	'disk'=>'server',
    	//bibit Aliases
    	'info'=>'bibit',
    	'mikrotik_list'=>'bibit',
    ];
    
    $args = explode(' ', trim($message));
    
    $command = ltrim(array_shift($args), '/');
    $method = '';
    if (isset($args[0]) && in_array($args[0], $arguments[$command])) {
    	$method = array_shift($args);
    }
    else { 
    	if (in_array($command, array_keys($alias))) {
    		$method = $command;
    		$command = $alias[$command];
    	}
    }
    
    
    switch ($command) {
      case 'mikrotik':
        $class = 'Mikrotik';
    		break;
    	case 'server':
    		$class = 'Server';
    		break;
    	case 'help':
    		$class = 'Help';
    		break;
    	case 'bibit':
    	  $class = 'Bibit';
    	  break;
    	default:
    		$class = 'Bot';
    }
    
    $hook = new $class($conf, $chat_id);
    
    if (!$hook->isTrusted()) {
    	$hook->unauthorized();
    	die();
    }
    
    if (!in_array($command, $commands)) {
    	$hook->unknown();
    }
    
    else {
    	if (isset($arguments[$command]) && in_array($method, $arguments[$command])) {
    		$hook->{$method}($args);
    		die();
    	} else if (in_array($command, $commands)) {
    		$hook->{$command}($args);
    	} else {
    		$hook->unknown();
    	}
    }

  }
  
  public function debug()
  {
    echo("debug");
  }
  
}
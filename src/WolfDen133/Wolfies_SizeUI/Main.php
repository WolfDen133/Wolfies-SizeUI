<?php

namespace WolfDen133\Wolfies_SizeUI;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;

use pocketmine\utils\Config;

class main extends PluginBase implements Listener {

	public function onEnable(){
		$this->getLogger()->info("§aEnabled Wolfie's SizeUI By WolfDen133");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$config = new Config($this->getDataFolder() . "Player.yml", Config::YAML);
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->getResource("config.yml");

	}

	public function onDisable(){
		$this->getLogger()->info("§cDisabled Wolfie's SizeUI By WolfDen133");
	}
	public function onJoin(PlayerJoinEvent $jevent){
        $player = $jevent->getPlayer();
		$config = new Config($this->getDataFolder() . "Player.yml", Config::YAML);
		if (!$config->exists($player->getName())){
			$config->set($player->getName(), 1);
			$config->save();
			$player->setScale(1);
		} else {
        	$psize = $config->get($player->getName());
			$player->setScale($psize);
		}
    }

	public function onCommand(CommandSender $sender, Command $cmd, String $label, Array $args) : bool {

		switch($cmd->getName()){
			case "size":
			if($sender instanceof Player){
                if($sender->hasPermission("command.size.use")){
					if($this->getConfig()->get('Type') == "Preset"){
						$this->openSizeP($sender);
                    }elseif($this->getConfig()->get('Type') == "Custom"){
						$this->openSizeC($sender);
                    }
				} else {
					$sender->sendMessage("§cYou Do Not Have Permission To Use This Command!");
				}	
			}
		}
	return true;
	}
	public function openSizeP($player){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			switch ($result){
				case 0:
					$apsize = 0.5;
					$player->setScale($apsize);
					$msgsize = $player->getScale();
					$config = new Config($this->getDataFolder() . "Player.yml", Config::YAML);
					$config->set($player->getName(), 0.5);
					$config->save();
					$player->sendMessage("§aYour size is now: §3" . $msgsize . "§a!");
				break;
				case 1:
					$apsize = 0.75;
					$player->setScale($apsize);
					$msgsize = $player->getScale();
					$config = new Config($this->getDataFolder() . "Player.yml", Config::YAML);
					$config->set($player->getName(), 0.75);
					$config->save();
					$player->sendMessage("§aYour size is now: §3" . $msgsize . "§a!");
				break;
				case 2:
					$apsize = 1.25;
					$player->setScale($apsize);
					$msgsize = $player->getScale();
					$config = new Config($this->getDataFolder() . "Player.yml", Config::YAML);
					$config->set($player->getName(), 1.25);
					$config->save();
					$player->sendMessage("§aYour size is now: §3" . $msgsize . "§a!");
				break;
				case 3:
					$apsize = 1.50;
					$player->setScale($apsize);
					$msgsize = $player->getScale();
					$config = new Config($this->getDataFolder() . "Player.yml", Config::YAML);
					$config->set($player->getName(), 1.5);
					$config->save();
					$player->sendMessage("§aYour size is now: §3" . $msgsize . "§a!");
				break;
				case 4:
					$apsize = 1;
					$player->setScale($apsize);
					$msgsize = $player->getScale();
					$config = new Config($this->getDataFolder() . "Player.yml", Config::YAML);
					$config->set($player->getName(), 1);
					$config->save();
					$player->sendMessage("§aYour size is now: §3" . $msgsize . "§a!");
				break;
				case 5:
					$msgsize = $player->getScale();
					$player->sendMessage("§aYour size is now: §3" . $msgsize . "§a!");
				break;


			}
		});
		$form->setTitle("§l§bSizeUI");
		$size = $player->getScale();
		$form->setContent("§6Your size: §3". $size);
		$form->addButton("§a0.5\n§eSets your size to 0.5!", 0, "textures/ui/down_arrow");
		$form->addButton("§a0.75\n§eSets your size to 0.75!", 0, "textures/ui/down_arrow");
		$form->addButton("§a1.25\n§eSets your size to 1.25!", 0, "textures/ui/up_arrow");
		$form->addButton("§a1.5\n§eSets your size to 1.5!", 0, "textures/ui/up_arrow");
		$form->addButton("§gReset%\n§eSets your size to 1", 0, "textures/ui/refresh_light");
		$form->addButton("§4Close", 0, "textures/ui/realms_red_x");
		$form->sendToPlayer($player);
		return $form;		
    }
	public function openSizeC($player){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(function (Player $player, array $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			$player->setScale($data[1]/100);
			$msgsize = $player->getScale();
			$config = new Config($this->getDataFolder() . "Player.yml", Config::YAML);
			$config->set($player->getName(), $data[1]/100);
			$config->save();
			$player->sendMessage("§aYour size is now: §3" . $msgsize . "§a!");
		});
		$form->setTitle($this->getConfig()->get('Title'));
		$form->addLabel($this->getConfig()->get('Content'));
		$lowsize=$this->getConfig()->get('Shortest');
		$highsize=$this->getConfig()->get('Tallest');
		$default=$this->getConfig()->get('Default');
		$steps=$this->getConfig()->get('Steps');
		$form->addSlider("§eSize", $lowsize, $highsize, $steps, $default);
		$form->sendToPlayer($player);
		return $form;		
	}
}
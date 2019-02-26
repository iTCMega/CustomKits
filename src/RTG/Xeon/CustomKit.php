<?php

/**
	* All rights reserved Xeon
	* Website: https://xeonpe.com
	* Author: InspectorGadget
*/

namespace RTG\Xeon;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\utils\Config;

use pocketmine\item\Item;
use pocketmine\entity\Effect;
use pocketmine\item\enchantment\Enchantment;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class CustomKit extends PluginBase implements Listener {
	
	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->warning("
		* Starting CustomKit
		* https://xeonpe.com
		* Author: InspectorGadget
		* Version: 1.0.0
		");
		$api = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		
		if($api === null) {
			$this->getLogger()->critical("Please install EconomyAPI! Disabling CustomKit");
			$this->setEnabled(false);
		}
		else {
		$this->saveResource("config.yml");
		$this->cfg = new Config($this->getDataFolder() . "config.yml");
		$enable = $this->cfg->get("Money");
		}
		
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $param) {
		switch(strtolower($cmd->getName())) {
			
			case "kit":
			if($sender->hasPermission("kit.command") && $sender instanceof Player) {
				if(isset($param[0])) {
					switch(strtolower($param[0])) {
						
						case "infinity":
							$api = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
							$money = $api->myMoney($sender);
							$infinity = $this->cfg->get("Infinity");
							$enable = $this->cfg->get("Money");
								if($enable === true) {
									if($money < $infinity) {
										$sender->sendMessage("§f[§eXeon§f] §aYou need at least $infinity to purchase this Kit!");
									}
									else {
										$this->Infinity($sender);
										$api->reduceMoney($sender->getName(), $infinity);
									}
								}
								else {
									$this->Infinity($sender);
								}
							return true;
						break;
						
						case "legendary":
							$api = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
							$money = $api->myMoney($sender);
							$le = $this->cfg->get("Legendary");
							$enable = $this->cfg->get("Money");
								if($enable === true) {
									if($money < $le) {
										$sender->sendMessage("§f[§eXeon§f] §aYou need at least $le to purchase this kit!");
									}
									else {
										$this->Legendary($sender);
										$api->reduceMoney($sender->getName(), $le);
									}
								}
								else {
									$this->Legendary($sender);
								}
							return true;
						break;
					
					}
				}
				else {
					$sender->sendMessage("§f[§eXeon§] Usage: /kit < legendary | infinity >");
				}
			}
			else {
				$sender->sendMessage("§cYou have no permission to use this command!");
			}
				return true;
			break;
		}
	}
	
	public function Infinity(Player $player) {
	
		// Item gather
		$ds = Item::get(276, 0, 1); // D Sword - line 117
		$h = Item::get(310, 0, 1); // D Helmet
		$c = Item::get(311, 0, 1); // D Chestpiece
		$l = Item::get(312, 0, 1); // D Legging
		$b = Item::get(313, 0, 1); // D Boot
		// close Item gather
		
		// renaming
		$ds->setCustomName("§l§f[KIT] §r§bInfinity\n§c50 percent chance of Nausea Effect on others!\nSharpness II");
		$h->setCustomName("§l§f[KIT] §r§cInfinity\n§eExplosion Protection");
		$c->setCustomName("§l§f[KIT] §r§eInfinity\n§eExplosion Protection");
		$l->setCustomName("§l§f[KIT] §r§dInfinity\n§eExplosion Protection");
		$b->setCustomName("§l§f[KIT] §r§aInfinity\n§eExplosion Protection");
		// close renaming
		
		// enchantment
		$ex = Enchantment::getEnchantment(3);
		$s = Enchantment::getEnchantment(9);
		$s->setLevel(2);
		$h->addEnchantment($ex);
		$c->addEnchantment($ex);
		$l->addEnchantment($ex);
		$b->addEnchantment($ex);
		$k = Enchantment::getEnchantment(20);
		$ds->addEnchantment($k);
		$ds->addEnchantment($s);
		// close enchantment
		
		// add
		$player->getInventory()->setItemInHand($ds);
		$player->getInventory()->setHelmet($h);
		$player->getInventory()->setChestplate($c);
		$player->getInventory()->setLeggings($l);
		$player->getInventory()->setBoots($b);
		// close add
		
		$enable = $this->cfg->get("Money");
		if($enable === true) {
			$player->sendMessage("§f§l[§eXeon§f] §r§cYou have successfully purchased a kit named Infinity!");
		}
		else {
			$player->sendMessage("§f§l[§eXeon§f] §r§aYou have successfully claimed a kit named Infinity!");
		}
	}
	
	public function Legendary(Player $player) {
		
		// Item Gather
		$ds = Item::get(276, 0, 1); // D Sword
		$h = Item::get(310, 0, 1); // D Helmet
		$c = Item::get(311, 0, 1); // D Chestpiece
		$l = Item::get(312, 0, 1); // D Legging
		$b = Item::get(313, 0, 1); // D Boot
		// close Item Gather
		
		//renaming
		$ds->setCustomName("§l§f[KIT] §cLegendary\n§e50 percent chance of Wither Effect on others\n§a75 percent chance of Confusing others");
		$h->setCustomName("§l§f[KIT] §bLegendary\n§eProtection II");
		$c->setCustomName("§l§f[KIT] §aLegendary\n§cProtection II");
		$l->setCustomName("§l§f[KIT] §dLegendary\n§eProtection II");
		$b->setCustomName("§l§f[KIT] §9Legendary\n§Protection II");
		// close renaming
		
		// Enchantment
		$p = Enchantment::getEnchantment(0);
		$p->setLevel(2);
		$f = Enchantment::getEnchantment(13);
		$ds->addEnchantment($f);
		$h->addEnchantment($p);
		$c->addEnchantment($p);
		$l->addEnchantment($p);
		$b->addEnchantment($p);
		// close enchantment
		
		// add Item
		$player->getInventory()->setItemInHand($ds);
		$player->getInventory()->setHelmet($h);
		$player->getInventory()->setChestplate($c);
		$player->getInventory()->setLeggings($l);
		$player->getInventory()->setBoots($b);
		// close add item
		
		$enable = $this->cfg->get("Money");
		if($enable === true) {
			$player->sendMessage("§f§l[§eXeon§f] §r§eYou have successfully purchased a kit named Legendary!");
		}
		else {
			$player->sendMessage("§f§l[§eXeon§f] You habe successfully claimed a kit named Legendary!");
		}
	}
	
	public function onDamage(EntityDamageEvent $e) {
			$en = $e->getEntity();
		if($e instanceof EntityDamageByEntityEvent) {
			$d = $e->getDamager();
			$h = $d->getItemInHand();
			
				if($h->getCustomName() === "§l§f[KIT] §r§bInfinity\n§c50 percent chance of nausea effect on others!\nSharpness II") {
					switch(mt_rand(1,10)) {
					
						case "1":
						break;
						case "2":
						break;
						case "3":
						break;
						case "4":
						break;
						case "5":
						break;
						case "6":
						break;
						case "7":
						break;
						case "8":
						break;
						case "9":
						break;
						case "10":
							$nausea = Effect::getEffect(9);
							$nausea->setDuration(13 * 20);
							$nausea->setAmplifier(1);
							$en->addEffect($nausea);
							$en->sendMessage("§l§cNAUSEA ACTIVATED!");
							$d->sendMessage("Nausea Activated!");
						break;
						
					}
				}
				
				if($h->getCustomName() === "§l§f[KIT] §cLegendary\n§e50 percent chance of Wither Effect on others\n§a75 percent chance of Confusing others") {
					switch(mt_rand(1,10)) {
						
						case "1":
						break;
						case "2":
						break;
						case "3":
						break;
						case "4":
						break;
						case "5":
							$c = Effect::getEffect(9);
							$c->setDuration(13 * 20);
							$c->setAmplifier(1);
							$en->addEffect(9);
							$en->sendMessage("§cYOU HAVE BEEN CONFUSED!");
							$d->sendMessage("Confusion Activated!");
						break;
						case "6":
						break;
						case "7":
						break;
						case "8":
							$w = Effect::getEffect(20);
							$w->setDuration(13 * 20);
							$w->setAmplifier(1);
							$en->addEffect(20);
							$en->sendMessage("§c§lYOU HAVE BEEN WITHERED!");
							$d->sendMessage("Wither activated!");
						break;
						case "9":
						break;
						case "10":
						break;
					
					}
				}
		}
	}
	
	
	
	
}

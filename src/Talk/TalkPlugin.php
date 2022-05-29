<?php
declare(strict_types=1);

namespace Talk;

use onebone\economyapi\EconomyAPI;
use OnixUtils\OnixUtils;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use PrefixManager\Loader;
use function count;
use function implode;
use function trim;

class TalkPlugin extends PluginBase{

	protected function onEnable() : void{
		OnixUtils::command("톡", "500원을 소모하고 톡을 합니다.", ["talk"], false, function(CommandSender $sender, string $commandLabel, array $args) : void{
			if(!$sender instanceof Player){
				return;
			}
			if(trim($args[0] ?? "") !== ""){
				if(EconomyAPI::getInstance()->reduceMoney($sender, 500) === EconomyAPI::RET_SUCCESS){
					$message = implode(" ", $args);
					$clean = TextFormat::clean($message);

					if(count($this->getServer()->getOnlinePlayers()) >= 25){
						$recipents = $sender->getWorld()->getPlayers();
					}else{
						$recipents = $this->getServer()->getOnlinePlayers();
					}
					$prefix = Loader::getInstance()->getPrefixManager()->getPrefix($sender);
					$this->getServer()->broadcastMessage("§d§l[§f톡§d]§r" . $prefix->getNowPrefix() . "§r " . $sender->getName() . ": §7" . $message, $recipents);
				}else{
					OnixUtils::message($sender, "돈이 부족합니다.");
				}
			}else{
				OnixUtils::message($sender, "할 말을 작성해주세요.");
			}
		});
	}
}
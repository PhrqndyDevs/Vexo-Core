<?php

namespace VexoCore;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\Server;

class Main extends PluginBase {
    protected function onEnable(): void {
        $this->getLogger()->info(TextFormat::GREEN . "VexoCore Plugin Enabled!");
        $this->saveDefaultConfig(); // Load default config
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        switch ($command->getName()) {
            case "vexo":
                if ($sender instanceof Player) {
                    $this->displayPlayerInfo($sender);
                    return true;
                } else {
                    $sender->sendMessage(TextFormat::RED . "This command can only be used in-game.");
                    return false;
                }
            case "serverinfo":
                $this->displayServerInfo($sender);
                return true;
        }
        return false;
    }

    private function displayPlayerInfo(Player $player): void {
        $ping = $player->getPing();
        $username = $player->getName();
        $ip = $player->getAddress();
        $location = $player->getPosition();
        $health = $player->getHealth();
        $maxHealth = $player->getMaxHealth();
        $experience = $player->getXp();
        $level = $player->getXpLevel();

        $player->sendMessage(TextFormat::AQUA . $this->getConfig()->get("messages")["info_header"]);
        $player->sendMessage(TextFormat::YELLOW . str_replace("{username}", $username, $this->getConfig()->get("messages")["username"]));
        $player->sendMessage(TextFormat::YELLOW . str_replace("{ping}", $ping, $this->getConfig()->get("messages")["ping"]));
        $player->sendMessage(TextFormat::YELLOW . str_replace("{ip}", $ip, $this->getConfig()->get("messages")["ip"]));
        $player->sendMessage(TextFormat::YELLOW . str_replace("{location}", "($location->getX(), $location->getY(), $location->getZ())", $this->getConfig()->get("messages")["location"]));
        $player->sendMessage(TextFormat::YELLOW . str_replace("{health}", "$health/$maxHealth", $this->getConfig()->get("messages")["health"]));
        $player->sendMessage(TextFormat::YELLOW . str_replace("{experience}", "$experience (Level $level)", $this->getConfig()->get("messages")["experience"]));
    }

    private function displayServerInfo(CommandSender $sender): void {
        $onlinePlayers = count(Server::getInstance()->getOnlinePlayers());
        $maxPlayers = Server::getInstance()->getMaxPlayers();
        $uptime = time() - Server::getInstance()->getStartTime();
        $memoryUsage = memory_get_usage();
        $formattedUptime = gmdate("H:i:s", $uptime);

        $sender->sendMessage(TextFormat::AQUA . "--- Server Info ---");
        $sender->sendMessage(TextFormat::YELLOW . "Online Players: " . TextFormat::LIGHT_PURPLE . "$onlinePlayers/$maxPlayers");
        $sender->sendMessage(TextFormat::YELLOW . "Uptime: " . TextFormat::LIGHT_PURPLE . $formattedUptime);
        $sender->sendMessage(TextFormat::YELLOW . "Memory Usage: "
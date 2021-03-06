<?php

namespace Wertzui123\BedrockClans\commands\subcommands;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use Wertzui123\BedrockClans\Main;

class home extends Subcommand
{

    /**
     * home constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        parent::__construct($plugin);
    }

    public function canUse(CommandSender $sender): bool
    {
        return $sender instanceof Player;
    }

    public function execute(CommandSender $sender, array $args)
    {
        if ($this->plugin->getConfig()->getNested('home.enabled') !== true) {
            $sender->sendMessage($this->plugin->getMessage('command.home.disabled'));
            return;
        }
        $player = $this->plugin->getPlayer($sender);
        $clan = $player->getClan();
        if (is_null($clan)) {
            $sender->sendMessage($this->plugin->getMessage('command.home.noClan'));
            return;
        }
        if (is_null($clan->getHome())) {
            $sender->sendMessage($this->plugin->getMessage('command.home.noHome'));
            return;
        }elseif(!$clan->getHome()->isValid() && !$this->plugin->getServer()->isLevelGenerated($clan->homeLevel)){
            $clan->setHome(null);
            $sender->sendMessage($this->plugin->getMessage('command.home.noHome'));
            return;
        }
        iF(is_null($clan->getHome()->getLevel())){
            $this->plugin->getServer()->loadLevel($clan->homeLevel);
            $clan->getHome()->setLevel($this->plugin->getServer()->getLevelByName($clan->homeLevel));
        }
        $sender->teleport($clan->getHome());
        $sender->sendMessage($this->plugin->getMessage('command.home.success'));
    }

}
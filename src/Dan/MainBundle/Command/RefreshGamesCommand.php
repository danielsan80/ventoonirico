<?php

namespace Dan\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class RefreshGamesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ventoonirico:games:refresh')
            ->setDescription('Refresh the local game repository')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $gameManager = $container->get('model.manager.game');
        $gameManager->refreshGames();
        $output->writeln('DONE');
    }
}

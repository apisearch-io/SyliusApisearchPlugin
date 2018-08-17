<?php

/*
 * This file is part of the Sylius Apisearch Plugin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Apisearch\SyliusApisearchPlugin\Command;

use Apisearch\SyliusApisearchPlugin\Populate\ResettingInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetCommand extends Command
{
    /**
     * @var ResettingInterface
     */
    private $resetting;

    /**
     * ResetCommand constructor.
     *
     * @param ResettingInterface $resetting
     */
    public function __construct(ResettingInterface $resetting)
    {
        parent::__construct();

        $this->resetting = $resetting;
    }

    protected function configure(): void
    {
        $this
            ->setName('apisearch:sylius:reset')
            ->setDescription('Reset product index')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('Resetting index');
        $this->resetting->reset();
    }
}

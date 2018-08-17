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

use Apisearch\SyliusApisearchPlugin\Populate\PopulateInterface;
use Apisearch\SyliusApisearchPlugin\Populate\ResettingInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateCommand extends Command
{
    /**
     * @var ResettingInterface
     */
    private $resetting;

    /**
     * @var PopulateInterface
     */
    private $populate;

    /**
     * PopulateCommand constructor.
     *
     * @param ResettingInterface $resetting
     * @param PopulateInterface $populate
     */
    public function __construct(ResettingInterface $resetting, PopulateInterface $populate)
    {
        parent::__construct();

        $this->resetting = $resetting;
        $this->populate = $populate;
    }

    protected function configure(): void
    {
        $this
            ->setName('apisearch:sylius:populate')
            ->addOption('no-reset', null, InputOption::VALUE_NONE, 'Do not reset index before populating')
            ->addOption('max-per-page', null, InputOption::VALUE_REQUIRED, 'The pager\'s page size', 100)
            ->setDescription('Populates search indexes')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $reset = !$input->getOption('no-reset');
        if ($reset) {
            $output->writeln('Resetting index');
            $this->resetting->reset();
        }

        $output->writeln('Populate index');
        $perPage = (int) $input->getOption('max-per-page');
        $this->populate->populate($perPage);
    }
}

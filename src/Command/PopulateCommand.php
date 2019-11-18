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

use Apisearch\SyliusApisearchPlugin\Indexing\PopulateInterface;
use Apisearch\SyliusApisearchPlugin\Indexing\ResettingInterface;
use Exception;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\Assert\Assert;

class PopulateCommand extends Command
{
    /** @var ResettingInterface */
    private $resetting;

    /** @var PopulateInterface */
    private $populate;

    /** @var ProductRepository */
    private $productRepository;

    /** @var array */
    private $searchProductAttribute = [
        'enabled' => true,
    ];

    /**
     * PopulateCommand constructor.
     */
    public function __construct(ResettingInterface $resetting, PopulateInterface $populate, ProductRepository $productRepository
) {
        parent::__construct();

        $this->resetting = $resetting;
        $this->populate = $populate;
        $this->productRepository = $productRepository;
    }

    protected function configure(): void
    {
        $this
            ->setName('apisearch:sylius:populate')
            ->addOption('no-reset', null, InputOption::VALUE_NONE, 'Do not reset index before populating')
            ->addOption('max-per-page', null, InputOption::VALUE_REQUIRED, 'The pager\'s page size', 100)
            ->setDescription('Populates product index')
        ;
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $perPage = (int) $input->getOption('max-per-page');
        Assert::greaterThan($perPage, 0);

        $reset = !$input->getOption('no-reset');
        if ($reset) {
            $output->writeln('Resetting index');
            $this->resetting->reset();
        }

        $output->writeln('Populate index');

        $countProductsToIndex = $this->productRepository->count(
            $this->searchProductAttribute
        );

        Assert::greaterThan($countProductsToIndex, 0);

        $progressBar = new ProgressBar($output, $countProductsToIndex);

        $page = 1;
        while (true) {
            $offset = ($page - 1) * $perPage;
            $model = $this->productRepository->findBy(
                $this->searchProductAttribute,
                [],
                $perPage,
                $offset
            );

            if (empty($model)) {
                $progressBar->finish();
                $output->writeln('');

                return;
            }

            /** @var ProductInterface $product */
            foreach ($model as $product) {
                $this->populate->populateSingle($product, false);
                $progressBar->advance();
            }

            $this->populate->flush();
            ++$page;
        }
    }
}

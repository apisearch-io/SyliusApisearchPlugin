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
use Apisearch\SyliusApisearchPlugin\Repository\ProductRepositoryInterface;
use Exception;
use function sprintf;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
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

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var LocaleProviderInterface */
    private $localeProvider;

    /**
     * PopulateCommand constructor.
     */
    public function __construct(
        ResettingInterface $resetting,
        PopulateInterface $populate,
        ProductRepositoryInterface $productRepository,
        LocaleProviderInterface $localeProvider
    ) {
        parent::__construct();

        $this->resetting = $resetting;
        $this->populate = $populate;
        $this->productRepository = $productRepository;
        $this->localeProvider = $localeProvider;
    }

    protected function configure(): void
    {
        $this
            ->setName('apisearch:sylius:populate')
            ->addOption('no-reset', null, InputOption::VALUE_NONE, 'Do not reset index before populating')
            ->addOption('max-per-page', null, InputOption::VALUE_REQUIRED, 'The pager\'s page size', 100)
            ->setDescription('Populate product index')
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
            $output->writeln('Resetting product index');
            $this->resetting->reset();
            $output->writeln(['<info>Done</info>', '']);
        }

        $output->writeln('Populate product index');

        foreach ($this->localeProvider->getAvailableLocalesCodes() as $locale) {
            $output->writeln(sprintf('<options=bold,underscore>Locale "%s"</>', $locale));

            $progressBar = new ProgressBar(
                $output,
                $this->productRepository->countEnabledByLocale($locale)
            );

            $page = 1;
            while (true) {
                $offset = ($page - 1) * $perPage;
                $model = $this->productRepository->findEnabledByLocale($locale, $perPage, $offset);

                if (empty($model)) {
                    $progressBar->finish();
                    $output->writeln(['', '']);

                    break;
                }

                /** @var ProductInterface $product */
                foreach ($model as $product) {
                    $this->populate->populateSingle($product, $locale, false);
                    $progressBar->advance();
                }

                $this->populate->flush();
                ++$page;
            }
        }

        $output->writeln('<info>Done</info>');
    }
}

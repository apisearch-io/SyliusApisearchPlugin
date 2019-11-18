<?php

declare(strict_types=1);

namespace spec\Apisearch\SyliusApisearchPlugin\Configuration;

use Apisearch\Model\IndexUUID;
use Apisearch\SyliusApisearchPlugin\Configuration\ApisearchConfiguration;
use Apisearch\SyliusApisearchPlugin\Configuration\ApisearchConfigurationInterface;
use Apisearch\SyliusApisearchPlugin\Element;
use Exception;
use PhpSpec\ObjectBehavior;

class ApisearchConfigurationSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith(
            'static',
            true,
            true,
            false,
            [
                [
                    'name' => 'Attribute',
                    'field' => 'attribute_field',
                    'type' => 'attribute',
                ],
                [
                    'name' => 'Option',
                    'field' => 'option_field',
                    'type' => 'option',
                ],
            ],
            [
                1,
                2,
                3,
            ]);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ApisearchConfiguration::class);
    }

    function it_is_implements_interface(): void
    {
        $this->shouldHaveType(ApisearchConfigurationInterface::class);
    }

    function it_get_version(): void
    {
        $this->getVersion()->shouldBe('static');
    }

    function it_get_index(): void
    {
        $this->getIndex()->shouldBe(Element::INDEX_NAME);
    }

    function it_get_uuid_index(): void
    {
        $this->getIndexUUID()->shouldBeAnInstanceOf(IndexUUID::class);
    }

    function it_get_show_price_filter(): void
    {
        $this->isShowPriceFilter()->shouldBe(true);
    }

    function it_get_show_text_search(): void
    {
        $this->isShowTextSearch()->shouldBe(true);
    }

    function it_get_enable_autocomplete(): void
    {
        $this->isEnableAutocomplete()->shouldBe(false);
    }

    function it_get_all_filters(): void
    {
        $this->getFilters()->shouldBe(
            [
                [
                    'name' => 'Attribute',
                    'field' => 'attribute_field',
                    'type' => 'attribute',
                ],
                [
                    'name' => 'Option',
                    'field' => 'option_field',
                    'type' => 'option',
                ],
            ]
        );
    }

    function it_get_all_attribute_filters(): void
    {
        $this->getFilters('attribute')->shouldBe(
            [
                0 => [
                    'name' => 'Attribute',
                    'field' => 'attribute_field',
                    'type' => 'attribute',
                ],
            ]
        );
    }

    function it_get_all_option_filters(): void
    {
        $this->getFilters('option')->shouldBe(
            [
                1 => [
                    'name' => 'Option',
                    'field' => 'option_field',
                    'type' => 'option',
                ],
            ]
        );
    }

    function it_get_pagination_size(): void
    {
        $this->getPaginationSize()->shouldBe([1, 2, 3]);
    }

    function it_throws_exception_if_pagination_size_is_not_set_up(): void
    {
        $this->beConstructedWith('static', true, true, true, [], []);

        $this->shouldThrow(Exception::class)->during('getPaginationSize');
    }
}

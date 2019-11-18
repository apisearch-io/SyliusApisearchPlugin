# Sylius Apisearch Plugin - Installation Guide

Open `config/bundles.php` and add:

```php
    Apisearch\ApisearchBundle::class => ['all' => true],
    Apisearch\SyliusApisearchPlugin\SyliusApisearchPlugin::class => ['all' => true],
```

Next go into `config/packages` and create file `apisearch.yaml` with example content, it is working great with fresh Sylius installation.

```yaml
parameters:
    env(APISEARCH_TOKEN): '0e4d75ba-c640-44c1-a745-06ee51db4e93'
    env(APISEARCH_HOST): 'dockerhost:8100'

apisearch:
    repositories:
        products:
            app_id: sylius
            token: '%env(APISEARCH_TOKEN)%'
            endpoint: '%env(APISEARCH_HOST)%'
            indices:
                products: products

sylius_apisearch:
    version: static # static, dynamic
    show_price_filter: true
    show_text_search: true
    enable_autocomplete: true
    pagination_size: [60,120,180]
    filters:
        brand:
            name: Brand
            field: t_shirt_brand
            precision: 8 #https://github.com/apisearch-io/php-client/blob/13179cac60cde59ebb84b83d8ea0d95cb35b6e70/Query/Filter.php#L44
            aggregate: true
            aggregation_sort: ['_term', 'asc']
            type: attribute
        size:
            name: Size
            field: t_shirt_size
            precision: 8 #https://github.com/apisearch-io/php-client/blob/13179cac60cde59ebb84b83d8ea0d95cb35b6e70/Query/Filter.php#L44
            aggregate: true
            aggregation_sort: ['_term', 'asc']
            type: option

```

Now time for routing, so open `config/routes/sylius_shop.yml` and add:

```yaml
sylius_apisearch:
    resource: "@SyliusApisearchPlugin/Resources/config/shop_routing.yml"
```

Execute those commands:

```bash
bin/console assets:install
bin/console apisearch:sylius:populate
```

That's all!

*Recipes is not available for this project for now.*




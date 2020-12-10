(function ($) {

  $('.apisearch--search-button').on('click', function (e) {
    e.preventDefault();

    let $this = $(this);
    let $clearUrl = $this.data('url');
    let $query = $($this.data('target')).val();
    let $connector = '?';

    if ($clearUrl.indexOf('?') > -1) {
      $connector = '&';
    }

    location.href = $clearUrl + $connector + 'q=' + $query;
  });

  $('.apisearch--filter-price-button').on('click', function (e) {
    e.preventDefault();

    let $this = $(this);
    let $clearUrl = $this.data('url');
    let $priceMin = $($this.data('price-min')).val();
    let $priceMax = $($this.data('price-max')).val();
    let $connector = '?';

    if ($clearUrl.indexOf('?') > -1) {
      $connector = '&';
    }

    $clearUrl += $connector;
    if ($priceMin !== '') {
      $clearUrl += 'price_min=' + $priceMin;
    }

    if ($priceMax !== '') {
      $clearUrl += '&price_max=' + $priceMax
    }

    location.href = $clearUrl;
  });

})(jQuery);

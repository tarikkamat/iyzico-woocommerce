(function ($, window, document) {
    $(".composite_data").on(
      "wc-composite-initializing",
      function (event, composite) {
        var obj = composite.api;
        composite.actions.add_action(
          "composite_totals_changed",
          function () {
            window.iyzicoInstallmentPrice = obj.get_composite_totals().price;
          },
          100,
          obj
        );
      }
    );
  })(jQuery, window, document);
  
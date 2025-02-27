(function ($, window, document) {
    $(document).ready(() => {
      render(iyzicoInstallmentObject.price);
    });
    $(".composite_data").on("wc-composite-initializing", (event, composite) => {
      var obj = composite.api;
      composite.actions.add_action(
        "composite_totals_changed",
        function () {
          render(obj.get_composite_totals().price);
        },
        100,
        obj
      );
    });
  
    function render(price) {
      price = parseFloat(price);
      if (price !== 0) {
        const container = $(".iyzico-installment-container");
        const rates = iyzicoInstallmentObject.rates;
        const assetUrl = iyzicoInstallmentObject.assetUrl;
        const symbol = iyzicoInstallmentObject.symbol;
  
        let html = "";
  
        $.each(rates, (family, data) => {
          html += '<div class="iyzico-installment-table-container">';
          html += `<center><img src="${assetUrl}/images/${family}.png"  alt="kredi kartı taksit" min-width="70" min-height="30"></center>`;
          html += '<table class="iyzico-installment-table">';
          html += `<tr style="background-color:${getColor(family)};color:#fff">`;
          html += "<td>Taksit Sayısı</td><td>Taksit Tutarı</td><td>Toplam</td>";
          html += "</tr>";
          $.each(data, (installment, rate) => {
            html += "<tr>";
            html += `<td>${installment}</td>`;
            html += `<td>${calculate(
              price,
              rate,
              installment
            )} <span>${symbol}</span></td>`;
            html += `<td>${calculate(price, rate)} <span>${symbol}</span></td>`;
            html += "</tr>";
          });
          html += "</table>";
          html += "</div>";
        });
  
        container.html(html);
      }
    }
  
    function calculate(price, rate, installment = 1) {
      rate = parseFloat(rate);
      price = parseFloat(price);
      let lastPrice = (rate / 100) * price + price;
      let result = new Intl.NumberFormat("tr-TR", {
        style: "currency",
        currency: "TRY",
        minimumFractionDigits: 2,
      })
        .format(lastPrice / installment)
        .replace("₺", "");
      return result;
    }
  
    function getColor(family) {
      let bgColor = "#fff";
      switch (family) {
        case "Maximum":
          bgColor = "#EC018C";
          break;
        case "Cardfinans":
          bgColor = "#294AA4";
          break;
        case "Paraf":
          bgColor = "#03DCFF";
          break;
        case "World":
          bgColor = "#9D69A7";
          break;
        case "Axess":
          bgColor = "#FEC30E";
          break;
        case "Bonus":
          bgColor = "#64C25A";
          break;
        case "BankkartCombo":
          bgColor = "#EC0C10";
          break;
        case "Advantage":
          bgColor = "#EB724F";
          break;
        case "SağlamKart":
          bgColor = "#006748";
          break;
        default:
          bgColor = "";
          break;
      }
  
      return bgColor;
    }
  })(jQuery, window, document);
jQuery(document).ready(function($) {
    var categorySelect = $('#woocommerce_iyzico_category_select');
    var mappingInput = $('#woocommerce_iyzico_category_installment_mapping');
    var checkboxPrefix = 'woocommerce_iyzico_installment_';

    try {
        var categoryInstallmentMapping = JSON.parse(mappingInput.val() || '{}');
    } catch (e) {
        categoryInstallmentMapping = {};
    }

    var installmentCheckboxes = $('input[id^="' + checkboxPrefix + '"]');

    var checkboxContainer = $('tr[data-installment-row="true"]').parent();

    if (checkboxContainer.length > 0) {
        checkboxContainer.css({
            'display': 'flex',
            'flex-wrap': 'wrap',
            'gap': '10px'
        });

        $('tr[data-installment-row="true"]').css({
            'flex': '0 0 auto',
            'margin-right': '20px',
            'min-width': '150px'
        });
    }

    categorySelect.on('change', function() {
        var selectedCategory = $(this).val();
        
        // Tüm checkbox'ları önce temizleyelim
        installmentCheckboxes.prop('checked', false);

        // Seçili kategori ve mapping kontrolü
        if (selectedCategory && categoryInstallmentMapping[selectedCategory]) {
            categoryInstallmentMapping[selectedCategory].forEach(function(installment) {
                var checkboxId = checkboxPrefix + installment;
                $('#' + checkboxId).prop('checked', true);
            });
        }
    });

    installmentCheckboxes.on('change', function() {
        var selectedCategory = categorySelect.val();
        if (!selectedCategory) {
            return;
        }

        var selectedInstallments = [];
        installmentCheckboxes.each(function() {
            if ($(this).is(':checked')) {
                var installmentNumber = $(this).attr('id').replace(checkboxPrefix, '');
                selectedInstallments.push(installmentNumber);
            }
        });

        if (selectedInstallments.length > 0) {
            categoryInstallmentMapping[selectedCategory] = selectedInstallments;
        } else {
            delete categoryInstallmentMapping[selectedCategory];
        }

        mappingInput.val(JSON.stringify(categoryInstallmentMapping));
    });

    // Sayfa yüklendiğinde varsayılan kategori seçimini tetikleyelim
    $(document).ready(function() {
        if (categorySelect.val()) {
            categorySelect.trigger('change');
        }
    });
}); 
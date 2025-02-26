<?php

namespace Iyzico\IyzipayWoocommerce\Admin;

class SettingsPage
{
    public function __construct()
    {
        add_action('admin_init', [$this, 'registerAjaxHandler']);
    }

    public function registerAjaxHandler()
    {
        add_action('wp_ajax_iyzico_reset_installments', [$this, 'resetInstallments']);
    }

    public function resetInstallments()
    {
        check_ajax_referer('iyzico_reset_installments', 'nonce');

        if (!current_user_can('manage_woocommerce')) {
            wp_send_json_error('Unauthorized');
        }

        $settings = get_option('woocommerce_iyzico_settings', []);
        $settings['category_installment_mapping'] = '{}';
        update_option('woocommerce_iyzico_settings', $settings);

        wp_send_json_success(__('Installment settings for all categories have been reset successfully.', 'iyzico-woocommerce'));
    }

    public function getHtmlContent()
    {
        $logo_url = esc_url(PLUGIN_URL) . '/assets/images/iyzico_logo.png';
        $buttonText = __("Reset All Category Installment Settings", 'iyzico-woocommerce');

        $html = '<style>
            @media (max-width:768px){.iyziBrand{position:fixed;bottom:0;top:auto!important;right:0!important}}
            .iyziBrandLogo {
                background-image: url("' . $logo_url . '");
                background-size: contain;
                background-repeat: no-repeat;
                background-position: center;
                width: 250px;
                height: 100px;
                margin-left: auto;
            }
            .iyzico-reset-button {
                background-color: #e74c3c;
                color: white;
                border: none;
                padding: 8px 16px;
                border-radius: 4px;
                cursor: pointer;
                margin-top: 10px;
            }
            .iyzico-reset-button:hover {
                background-color: #c0392b;
            }
            #iyzico-reset-result {
                margin-top: 10px;
                padding: 10px;
                border-radius: 4px;
                display: none;
            }
            .iyzico-success {
                background-color: #2ecc71;
                color: white;
            }
            .iyzico-error {
                background-color: #e74c3c;
                color: white;
            }
        </style>
        <div class="iyziBrandWrap">
            <div class="iyziBrand" style="clear:both;position:absolute;right: 50px;top:440px;display: flex;flex-direction: column;justify-content: center;">
                <div class="iyziBrandLogo"></div>
                <p style="text-align:center;"><strong>Version: </strong>' . esc_html(IYZICO_PLUGIN_VERSION) . '</p>
                <button type="button" id="iyzico-reset-installments" class="iyzico-reset-button">' . $buttonText . '</button>
                <div id="iyzico-reset-result"></div>
            </div>
        </div>
        <script>
        jQuery(document).ready(function($) {
            $("#iyzico-reset-installments").on("click", function() {
                if (!confirm("Tüm kategori taksit ayarları sıfırlanacak. Emin misiniz?")) {
                    return;
                }

                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data: {
                        action: "iyzico_reset_installments",
                        nonce: "' . wp_create_nonce('iyzico_reset_installments') . '"
                    },
                    success: function(response) {
                        var resultDiv = $("#iyzico-reset-result");
                        if (response.success) {
                            resultDiv.html(response.data)
                                   .removeClass("iyzico-error")
                                   .addClass("iyzico-success")
                                   .show();
                            
                            // Taksit checkbox\'larını sıfırla
                            $("input[id^=\'woocommerce_iyzico_installment_\']").prop("checked", false);
                            
                            // Mapping input\'u sıfırla
                            $("#woocommerce_iyzico_category_installment_mapping").val("{}");
                            
                            setTimeout(function() {
                                resultDiv.fadeOut();
                            }, 3000);
                        } else {
                            resultDiv.html(response.data)
                                   .removeClass("iyzico-success")
                                   .addClass("iyzico-error")
                                   .show();
                        }
                    },
                    error: function() {
                        $("#iyzico-reset-result")
                            .html("İşlem sırasında bir hata oluştu.")
                            .removeClass("iyzico-success")
                            .addClass("iyzico-error")
                            .show();
                    }
                });
            });
        });
        </script>';

        $allowed_html = [
            'style' => [],
            'div' => ['class' => [], 'id' => [], 'style' => []],
            'p' => ['style' => []],
            'strong' => [],
            'button' => ['type' => [], 'id' => [], 'class' => []],
            'script' => [],
        ];

        echo wp_kses($html, $allowed_html);
    }
}

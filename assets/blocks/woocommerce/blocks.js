(()=>{"use strict";const e=window.wp.htmlEntities;var t=function(){return(0,e.decodeEntities)(c.description||"")},n=function(e){var t=e.components.PaymentMethodLabel;return React.createElement(t,{text:a})},c=(0,window.wc.wcSettings.getSetting)("iyzico_data",{}),a=(0,e.decodeEntities)(c.title);const o={name:"iyzico",label:React.createElement(n,null),content:React.createElement(t,null),edit:React.createElement(t,null),canMakePayment:function(){return!0},ariaLabel:a,supports:{features:c.supports}};var i=function(){return(0,e.decodeEntities)(l.description||"")},r=function(e){var t=e.components.PaymentMethodLabel;return React.createElement(t,{text:s})},l=(0,window.wc.wcSettings.getSetting)("pwi_data",{}),s=(0,e.decodeEntities)(l.title);const u={name:"pwi",label:React.createElement(r,null),content:React.createElement(i,null),edit:React.createElement(i,null),canMakePayment:function(){return!0},ariaLabel:s,supports:{features:l.supports}};var d=window.wc.wcBlocksRegistry.registerPaymentMethod;d(o),console.log("iyzico-woocommerce script loaded successfully."),d(u),console.log("pwi-woocommerce script loaded successfully.")})();
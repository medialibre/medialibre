(function($) {
    "use strict";

    $(function() {


        var pluginName = "cyrtrans";
        /**
         * Pseudo checkbox
         */
        $(".pseudo-checkbox").on("click",
            function() {
                //$(this).next().attr('checked', 'checked');
                $(this).toggleClass("checked");

                if ($(this).next().is(":checked")) {
                    $(this).next().attr("checked", false);
                } else {
                    $(this).next().attr("checked", true);
                }

            });

        $(".pseudo-checkbox-hidden").on("change",
            function() {
                $(this).prev().toggleClass("checked");
            });

        $(".pseudo-checkbox-hidden").on("focus",
            function() {
                $(this).prev().addClass("focused");
            });

        $(".pseudo-checkbox-hidden").on("blur",
            function() {
                $(this).prev().removeClass("focused");
            });


        $(".js-wptrans-instruction-expand").click(function() {
            $(".js-wptrans-instruction-body").slideToggle();
        });
        jQuery(document).on('click',
            '#trans_old',
            function () {
                $('#trans_old').attr("disabled", true);
                jQuery.ajax({
                    url: posttransliterate.ajax_url,
                    type: 'post',
                    data: {
                        action: 'cyrtrans_ajax_old'
                    },
                    success: function (result) {
                        //var result = JSON.parse(response);
                        if (result.terms > 0 || result.posts > 0) {
                            $("#trans_old")
                                .after(
                                '<div id="message" class="updated notice is-dismissible"><p>Транслитерирани ' + result.posts + ' поста и ' + result.terms + ' термина</p></div >');
                        } else {
                            $("#trans_old")
                                .after(
                                    '<div id="message" class="updated notice is-dismissible"><p>Не бяха открити стари постове с адреси на кирилица!</p></div >');
                        }
                        $(document).trigger("wp-updates-notice-added");
                       
                    }
                });
            });

        /**
         * Tabs
         */
        if (readCookie(`tab-${pluginName}`) != null) {
            const tabId = readCookie(`tab-${pluginName}`);
            const $tab = $(`.js-${pluginName} #${tabId}`);

            if ($tab.length) {
                $(".js-wptrans-tab-wrapper a").removeClass("wptrans-tab-active");
                $(`.js-${pluginName} #tab-${tabId}`).addClass("wptrans-tab-active");

                jQuery(".js-wptrans-tab-item").removeClass("active");
                jQuery(`#${tabId}`).addClass("active");
            }
        }

        $(`.js-${pluginName}`).on("click",
            ".js-wptrans-tab-wrapper a",
            function() {
                jQuery(".js-wptrans-tab-wrapper").find("a").removeClass("wptrans-tab-active");
                jQuery(this).addClass("wptrans-tab-active");
                var curtab = jQuery(this).attr("id").replace("tab-", "");
                createCookie(`tab-${pluginName}`, curtab);

                jQuery(".js-wptrans-tab-item").removeClass("active");
                jQuery("#"+curtab).addClass("active");

                return false;
            });

    });

})(jQuery);

function createCookie(name, value, days) {
    var expires;

    if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = `; expires=${date.toGMTString()}`;
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function readCookie(name) {
    const nameEQ = encodeURIComponent(name) + "=";
    const ca = document.cookie.split(";");
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === " ") c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}
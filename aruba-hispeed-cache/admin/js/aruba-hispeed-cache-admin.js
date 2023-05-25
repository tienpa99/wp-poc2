(() => {
    "use strict";

    const ahsc_show_option = () => {
        let ahsc_enable_purge = document.querySelector("input#ahsc_enable_purge");

        ahsc_enable_purge.addEventListener("change", (e) => {
            let form_parts = document.querySelector("form#post_form > div > div").children;

            if (form_parts[1].style.display === "none" && form_parts[2].style.display === "none") {
                form_parts[1].style.removeProperty("display");
                form_parts[2].style.removeProperty("display");
            } else {
                form_parts[1].style.display = "none";
                form_parts[2].style.display = "none";
            }
        });
    };

    document.addEventListener("DOMContentLoaded", function (event) {
        const purge_btn = document.querySelector("a#purgeall");

        purge_btn.addEventListener("click", (e) => {
            if (confirm(aruba_hispeed_cache.purge_confirm_string) === true) {
                // Continue submitting form.
            } else {
                e.preventDefault();
            }
        });

        ahsc_show_option();
    });
})();

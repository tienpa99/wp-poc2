(function ($) {
    const { isRTL } = siteDirection;
    let isEditMode;
    const hideToolbar = () => {
        let $section = $(".premium-container"),
            isStretched = $section.hasClass("premium-container__stretch_true");
        let $toolbar = $section
            .closest("div[data-type='premium/section']")
            .find(".editor-block-list__block-edit")
            .eq(0)
            .find(".components-toolbar")
            .eq(1);

        if (isStretched) {
            $toolbar.css("display", "none");
        } else {
            $toolbar.css("display", "flex");
        }
    };
    const stretchSection = () => {
        const $stretchedSections = $(".premium-container");
        $stretchedSections.map((index, section) => {
            let $section = $(section),
                isStretched = $section.hasClass("premium-container__stretch_true"),
                css = {};
            if (isStretched) {
                let width = $(window).outerWidth(),
                    offsetLeft = !isEditMode
                        ? $section.offset().left
                        : $section.offset().left - $("#adminmenuwrap").outerWidth(),
                    isFixed = "fixed" === $section.css("position"),
                    correctOffset = isFixed ? 0 : offsetLeft;
                if (isEditMode) {
                    width = $(".edit-post-layout__content").outerWidth();
                }
                if (!isFixed) {
                    if (isRTL) {
                        correctOffset = width - ($section.outerWidth() + correctOffset);
                    }
                    correctOffset = -correctOffset;
                }
                css.width = width + "px";
                let direction = isRTL ? "right" : "left";
                css[direction] = correctOffset + "px";
                $section.css(css);
            } else {
                css.width = "100%";
                let direction = isRTL ? "right" : "left";
                css[direction] = "0px";
                $section.css(css);
            }
        });
    };
    jQuery(document).ready(() => {
        setTimeout(() => {
            isEditMode = $(".edit-post-layout__content").length;
            stretchSection();
            hideToolbar();
            $(".premium-container").on("click", e => {
                let $checkBox = $(".components-form-toggle__input");
                hideToolbar();
                if ($checkBox.length) {
                    $checkBox.on("click", () => {
                        setTimeout(() => {
                            stretchSection();
                            hideToolbar();
                        }, 500);
                    });
                }
            });
        }, 1000);
    });
})(jQuery);

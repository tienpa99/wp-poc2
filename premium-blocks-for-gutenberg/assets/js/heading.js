jQuery(document).ready(function ($) {
    const $title = $(".premium-heading");
    var $titleContainer = $(".premium-title-container");

    if ($titleContainer.hasClass('style9')) {
        var $style9 = $(".premium-title-style9__wrap");

        $style9.each(function () {
            var elm = $(this);
            var holdTime = elm.attr('data-blur-delay') * 1000;
            elm.attr('data-animation-blur', 'process')
            elm.find('.premium-title-style9-letter').each(function (index, letter) {
                index += 1;
                var animateDelay;
                if ($('body').hasClass('rtl')) {
                    animateDelay = 0.2 / index + 's';
                } else {
                    animateDelay = index / 20 + 's';
                }
                $(letter).css({
                    '-webkit-animation-delay': animateDelay,
                    'animation-delay': animateDelay
                });
            })
            setInterval(function () {
                elm.attr('data-animation-blur', 'done')
                setTimeout(function () {
                    elm.attr('data-animation-blur', 'process')
                }, 150);
            }, holdTime);
        });
    }

    if ($titleContainer.hasClass('style8')) {
        var $style8 = $(".premium-title-style8__wrap");

        $style8.map((index, style) => {
            let $style = $(style)
            var holdTime = $style[0].dataset.blurDelay * 1000,
                duration = $style[0].dataset.shinyDur * 1000;

            function shinyEffect() {

                $style8.each(function () {
                    var elm = $(this);

                    let $newTitle = elm.find('.premium-title-text-title');

                    $newTitle.each(function () {
                        var $newelm = $(this);

                        $($newelm.get(0)).attr('data-animation', 'shiny');
                        setTimeout(function () {
                            $newelm.removeAttr('data-animation')
                        }, duration);
                    })
                })
            }

            (function repeat() {
                shinyEffect();
                setTimeout(repeat, holdTime);
            })();
        })
    }


});

jQuery(document).ready(function ($) {
    const $counters = $(".premium-countup__wrap");
    $counters.map((index, counter) => {
        let $counter = $(counter).find(".premium-countup__increment");
        let time = $counter.data("interval");
        let delay = $counter.data("delay");
        $counter.counterUp({
            delay: delay,
            time: time
        });
    });
});

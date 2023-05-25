jQuery(function ($) {
    const $contentSwitcher = $(".premium-content-switcher");

    $contentSwitcher.map((index, contentSwitcher) => {
        let $contentSwitcher = $(contentSwitcher);


        let $content = $contentSwitcher.find(`.premium-content-switcher-two-content`);
        $content[0].children[1].style.display = "none";
        $content[0].children[0].style.display = 'block';
        $content[0].style.display = 'block';

        let $toggleBox = $contentSwitcher.find(`.premium-content-switcher-toggle-switch-label input`);

        $toggleBox.on('change', function () {
            if ($(this).is(':checked')) {
                $content[0].children[1].style.display = 'block';
                $content[0].children[0].style.display = "none";
            }
            else {
                $content[0].children[0].style.display = 'block';
                $content[0].children[1].style.display = "none";
            }
        });
    });
});
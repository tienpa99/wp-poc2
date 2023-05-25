jQuery(function ($) {
    const $modals = $(".premium-modal-box");
    $modals.map((index, modal) => {
        let $modal = $(modal);
        let settings = $modal.data('trigger');
        const wrapClass = $modal.find('.premium-popup__modal_content')
        const closes = wrapClass.find('button.close-button');
        const wrapOverlay = $modal.find('.premium-popup__modal_wrap_overlay')
        function ShowModal() {
            $modal.find(".premium-popup__modal_wrap").css("display", "flex");
        }
        function hideModal() {
            $modal.find(".premium-popup__modal_wrap").css("display", "none");
        }
        closes.map((index, close) => {
            let closeButton = $(close)
            closeButton.click(hideModal)
        })
        if (settings === "load") {
            $(document).ready(function ($) {
                let delayTime = wrapClass.data('delay')

                setTimeout(ShowModal, delayTime * 1000);
            });
        }
        if (settings === "button") {
            let $button = $modal.find(' .premium-modal-trigger-container button')

            $button.click(ShowModal)
        }
        if (settings === "image") {
            let $image = $modal.find(' .premium-modal-trigger-container img')

            $image.click(ShowModal)
        }
        if (settings === "text") {
            let $textTrigger = $modal.find(' .premium-modal-trigger-container span')

            $textTrigger.click(ShowModal)
        }
        if (settings === "lottie") {
            let $lottieTrigger = $modal.find(' .premium-modal-trigger-container .premium-lottie-animation')

            $lottieTrigger.click(ShowModal)
        }

        wrapOverlay.click(hideModal)
    })
})



(function ($) {
    var inviewObjects = {},
        viewportSize,
        viewportOffset,
        d = document,
        w = window,
        documentElement = d.documentElement,
        expando = $.expando,
        timer;

    $.event.special.inview = {
        add: function (data) {
            inviewObjects[data.guid + "-" + this[expando]] = { data: data, $element: $(this) };
            if (!timer && !$.isEmptyObject(inviewObjects)) {
                timer = setInterval(checkInView, 250);
            }
        },

        remove: function (data) {
            try {
                delete inviewObjects[data.guid + "-" + this[expando]];
            } catch (e) { }
            if ($.isEmptyObject(inviewObjects)) {
                clearInterval(timer);
                timer = null;
            }
        },
    };

    function getViewportSize() {
        var mode,
            domObject,
            size = { height: w.innerHeight, width: w.innerWidth };
        if (!size.height) {
            mode = d.compatMode;
            if (mode || !$.support.boxModel) {
                // IE, Gecko
                domObject =
                    mode === "CSS1Compat"
                        ? documentElement // Standards
                        : d.body; // Quirks
                size = {
                    height: domObject.clientHeight,
                    width: domObject.clientWidth,
                };
            }
        }

        return size;
    }

    function getViewportOffset() {
        return {
            top: w.pageYOffset || documentElement.scrollTop || d.body.scrollTop,
            left: w.pageXOffset || documentElement.scrollLeft || d.body.scrollLeft,
        };
    }

    function checkInView() {
        var $elements = $(),
            elementsLength,
            i = 0;

        $.each(inviewObjects, function (i, inviewObject) {
            var selector = inviewObject.data.selector,
                $element = inviewObject.$element;
            $elements = $elements.add(selector ? $element.find(selector) : $element);
        });

        elementsLength = $elements.length;
        if (elementsLength) {
            viewportSize = viewportSize || getViewportSize();
            viewportOffset = viewportOffset || getViewportOffset();

            for (; i < elementsLength; i++) {
                if (!$.contains(documentElement, $elements[i])) {
                    continue;
                }

                var $element = $($elements[i]),
                    elementSize = { height: $element.height(), width: $element.width() },
                    elementOffset = $element.offset(),
                    inView = $element.data("inview"),
                    visiblePartX,
                    visiblePartY,
                    visiblePartsMerged;

                if (!viewportOffset || !viewportSize) {
                    return;
                }

                if (
                    elementOffset.top + elementSize.height > viewportOffset.top &&
                    elementOffset.top < viewportOffset.top + viewportSize.height &&
                    elementOffset.left + elementSize.width > viewportOffset.left &&
                    elementOffset.left < viewportOffset.left + viewportSize.width
                ) {
                    visiblePartX =
                        viewportOffset.left > elementOffset.left
                            ? "right"
                            : viewportOffset.left + viewportSize.width < elementOffset.left + elementSize.width
                                ? "left"
                                : "both";
                    visiblePartY =
                        viewportOffset.top > elementOffset.top
                            ? "bottom"
                            : viewportOffset.top + viewportSize.height < elementOffset.top + elementSize.height
                                ? "top"
                                : "both";
                    visiblePartsMerged = visiblePartX + "-" + visiblePartY;
                    if (!inView || inView !== visiblePartsMerged) {
                        $element
                            .data("inview", visiblePartsMerged)
                            .trigger("inview", [true, visiblePartX, visiblePartY]);
                    }
                } else if (inView) {
                    $element.data("inview", false).trigger("inview", [false]);
                }
            }
        }
    }

    $(w).bind("scroll resize scrollstop", function () {
        viewportSize = viewportOffset = null;
    });

    if (!documentElement.addEventListener && documentElement.attachEvent) {
        documentElement.attachEvent("onfocusin", function () {
            viewportOffset = null;
        });
    }
    setTimeout(() => {
        $(document).on('inview', '[data-premiumanimation]', function (event, visible, visiblePartX, visiblePartY) {
            var $this = $(this);

            if (visible) {
                let animation = $this.data('premiumanimation');
                if (animation && typeof animation.name != 'undefined' && animation.openAnimation != 0) {
                    setTimeout(() => {
                        $this.css({ opacity: 1 })
                    }, parseInt(animation.delay) + 100)
                    $this.css({
                        'animation-name': animation.name,
                        'animation-timing-function': animation.curve,
                        'animation-duration': animation.duration + 'ms',
                        'animation-delay': animation.delay + 'ms',
                        'animation-iteration-count': animation.repeat === 'once' ? 1 : 'infinite'
                    })
                }
            }
            $this.unbind('inview');
        });
    }, 1000);
})(jQuery);
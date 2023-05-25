const slideUpAccordion = (target, duration = 500) => {
    target.style.transitionProperty = "height, margin, padding";
    target.style.transitionDuration = duration + "ms";
    target.style.boxSizing = "border-box";
    target.style.height = target.offsetHeight + "px";
    target.offsetHeight;
    target.style.overflow = "hidden";
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    window.setTimeout(() => {
        target.style.display = "none";
        target.style.removeProperty("height");
        target.style.removeProperty("padding-top");
        target.style.removeProperty("padding-bottom");
        target.style.removeProperty("margin-top");
        target.style.removeProperty("margin-bottom");
        target.style.removeProperty("overflow");
        target.style.removeProperty("transition-duration");
        target.style.removeProperty("transition-property");
    }, duration);
};

const slideDownAccordion = (target, duration = 500) => {
    target.style.removeProperty("display");
    let display = window.getComputedStyle(target).display;

    if (display === "none") display = "block";

    target.style.display = display;
    let height = target.offsetHeight;
    target.style.overflow = "hidden";
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    target.offsetHeight;
    target.style.boxSizing = "border-box";
    target.style.transitionProperty = "height, margin, padding";
    target.style.transitionDuration = duration + "ms";
    target.style.height = height + "px";
    target.style.removeProperty("padding-top");
    target.style.removeProperty("padding-bottom");
    target.style.removeProperty("margin-top");
    target.style.removeProperty("margin-bottom");
    window.setTimeout(() => {
        target.style.removeProperty("height");
        target.style.removeProperty("overflow");
        target.style.removeProperty("transition-duration");
        target.style.removeProperty("transition-property");
    }, duration);
};
const slideToggleAccordion = (target, duration = 500) => {
    if (window.getComputedStyle(target).display === "none") {
        return slideDownAccordion(target, duration);
    } else {
        return slideUpAccordion(target, duration);
    }
};
setTimeout(() => {
    const accordions = document.querySelectorAll(".premium-accordion");

    if (accordions.length) {
        const closeOthers = (accordionItems, activeIndex) => {
            accordionItems.forEach((item, index) => {
                if (activeIndex !== index) {
                    const itemDescription = item.querySelector(
                        ".premium-accordion__desc_wrap"
                    );
                    const itemIcon = item.querySelector(
                        ".premium-accordion__icon"
                    );
                    slideUpAccordion(itemDescription, 500);
                    itemIcon.classList.add("premium-accordion__closed");
                }
            });
        };
        accordions.forEach((accordion) => {
            const accordionItems = accordion.querySelectorAll(
                ".premium-accordion__content_wrap"
            );
            const firstItem = accordionItems[0];
            const firstItemDescription = firstItem.querySelector(
                ".premium-accordion__desc_wrap"
            );
            const firstItemIcon = firstItem.querySelector(
                ".premium-accordion__desc_wrap"
            );
            slideDownAccordion(firstItemDescription, 500);
            firstItemIcon.classList.remove("premium-accordion__closed");
            closeOthers(accordionItems, 0);

            if (accordionItems.length) {
                accordionItems.forEach((item, index) => {
                    const itemTitle = item.querySelector(
                        ".premium-accordion__title_wrap"
                    );
                    const itemDescription = item.querySelector(
                        ".premium-accordion__desc_wrap"
                    );
                    const itemIcon = item.querySelector(
                        ".premium-accordion__icon"
                    );

                    itemTitle.addEventListener("click", () => {
                        slideToggleAccordion(itemDescription, 500);
                        itemIcon.classList.toggle("premium-accordion__closed");
                        closeOthers(accordionItems, index);
                    });
                });
            }
        });
    }
}, 200);

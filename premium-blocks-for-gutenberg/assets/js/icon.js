window.addEventListener("DOMContentLoaded", function (event) {
    var icons = document.querySelectorAll(".premium-icon");
    if (!icons) return;

    icons.forEach(function (icon) {

        var type = icon.getAttribute("data-icontype");
        if (type === "svg") {
            var svg = document.getElementById(
                "premium-icon-svg"
            )
            var src = svg.getAttribute("data-src");
            console.log(svg)
            svg.innerHTML = src
            return svg.firstElementChild
        }
    })
})
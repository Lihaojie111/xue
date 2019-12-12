(function () {
        var b = document.documentElement,
        a = function () {
            var a = b.getBoundingClientRect().width;
            // console.log(a);
            // 设计图宽度为640px。
            b.style.fontSize = (100/640) * (a >=640 ? 640 : a) + "px"
            // b.style.fontSize = (100/750) * a + "px";
        }, c = null;
        window.addEventListener("resize", function () {
            clearTimeout(c);
            c = setTimeout(a, 300)
        });
        a()
    })();
    //(a/750)*100
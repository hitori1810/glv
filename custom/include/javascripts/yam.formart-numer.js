(function (b) {
    b.fn.yamFormat = function (e) {
        var d = {
            type: "int",
            auto: false
        }, f = function (a) {
                a = a.which ? a.which : event.keyCode;
                return a > 31 && (a < 48 || a > 57) ? false : true
            }, h = function (a, g) {
                var c, b;
                if (window.event) c = window.event.keyCode;
                else if (g) c = g.which;
                else return true;
                b = String.fromCharCode(c);
                return c == null || c == 0 || c == 8 || c == 9 || c == 13 || c == 27 ? true : a.val().indexOf(".") > -1 ? "0123456789".indexOf(b) > -1 ? true : false : "0123456789.".indexOf(b) > -1 ? true : false
            };
        return this.each(function () {
            var a = b(this);
            e && b.extend(d, e);
            if (d.auto) a.find("input[int]").each(function () {
                b(this).keypress(function (a) {
                    return f(a)
                })
            }),
            a.find("input[float]").each(function () {
                b(this).keypress(function (a) {
                    return h(b(this), a)
                })
            });
            else switch (d.type) {
            case "float":
                a.keypress(function (a) {
                    return h(b(this), a)
                });
                break;
            default:
                a.keypress(function (a) {
                    return f(a)
                })
            }
        })
    }
})(jQuery);
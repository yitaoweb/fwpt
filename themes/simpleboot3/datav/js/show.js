(function(a) {
    var e = a.getElementsByTagName("head")[0];
    function t(t) {
        var n = a.createElement("script");
        n.src = t;
        e.appendChild(n);
        return n
    }
    var n = "//g.alicdn.com";
    var i = self.goldlog;
    if (i && i.getCdnPath) {
        n = i.getCdnPath()
    }
    n += "/secdev/";
    if (!a._sufei_data2) {
        var d = Math.random() < .7 ? "3.2.1": "3.1.9";
        var s = t(n + "sufei_data/" + d + "/index.js");
        s.id = "aplus-sufei"
    }
    var r = 1e-4;
    if (Math.random() < r) {
        setTimeout(function() {
            a["_linkstat_sample"] = r;
            t(n + "linkstat/index.js?v=1201")
        },
        1e3)
    }
})(document);
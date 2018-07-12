(function(app) {
    app.augment("config", {
        "appId": "SupportPortal",
        "appStatus": "offline",
        "env": "dev",
        "platform": "portal",
        "additionalComponents": {
            "header": {
                "target": "#header"
            },
            "footer": {
                "target": "#footer"
            }
        },
        "alertsEl": "#alert",
        "serverUrl": "http://localhost/cloudpro/rest/v10",
        "siteUrl": "http://localhost/cloudpro/",
        "unsecureRoutes": ["signup", "error"],
        "loadCss": "url",
        "clientID": "support_portal",
        "maxSearchQueryResult": "5"
    }, false);
})(SUGAR.App);
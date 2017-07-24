var system = require('system');
var args = system.args;
var page = require('webpage').create();


var url = args[1];
page.settings.resourceTimeout = args[2];
page.settings.javascriptEnabled = args[3];
page.settings.loadImages = args[4];
page.settings.userAgent = args[5];
page.settings.webSecurityEnabled = args[6];

page.open(url, function (status) {
    console.log(page.content);
    phantom.exit();
});

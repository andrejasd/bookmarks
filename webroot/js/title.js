"use strict";
var page = require('webpage').create(),
    system = require('system'),
    address;

//console.log('The default user agent is ' + page.settings.userAgent);
page.settings.userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36';

page.onError = function (msg, trace) {
// do nothing
};

if (system.args.length === 1) {
    phantom.exit(1);
} else {
    address = system.args[1];
    address = decodeURIComponent(address);
    page.open(address , function (status) {
        if (status === 'success') {
            console.log(page.title); // get page Title
            phantom.exit();
        }else {
            phantom.exit(1);
        }
    });
}
"use strict";
var page = require('webpage').create(),
    system = require('system'),
    address, title;

page.onConsoleMessage = function(msg) {
    console.log(msg);
};
console.log('OK');
title = 'asd';
if (system.args.length === 1) {
    phantom.exit(1);
} else {
    address = system.args[1];
    page.open(address, function (status) {
        if (status === 'success') {
            // Подключаем jQuery
            // https://code.jquery.com/jquery-3.1.0.min.js
            //page.includeJs('https://code.jquery.com/jquery-3.1.0.min.js', function() {
                page.evaluate(function() {
                    //title = 'asd';
                    //title = $('<title>').html();
                    title = window.document.title;
                    console.log(title);
                });
                console.log(title);
                phantom.exit(0);
            //});
        }else {
            phantom.exit(1);
        }
    });
}
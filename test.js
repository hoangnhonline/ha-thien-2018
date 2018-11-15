var casper = require('casper').create({
verbose: true,
logLevel: 'error',
pageSettings: {
    loadImages: false, // The WebPage instance used by Casper will
    loadPlugins: false, // use these settings
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36'
    }
});
casper.start();

//JAL公式サイト　
casper.thenOpen("https://stocks.exchange/trade/ZEL/BTC", function() {	
	casper.wait(7000, function() {
        this.echo(this.getTitle());
   	});

});
casper.run();
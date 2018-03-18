const webpackConfig = require('./vendor/sulu/sulu/webpack.config.js');

webpackConfig.entry.push(__dirname + '/src/Resources/js/index.js');

module.exports = webpackConfig;

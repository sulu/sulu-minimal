const path = require('path');
const webpackConfig = require('./../../vendor/sulu/sulu/webpack.config.js');

module.exports =  (env, argv) => {
    const config = webpackConfig(env, argv);
    config.output.path = path.resolve('../../web');

    return config;
};

const webpackConfig = require('./vendor/sulu/sulu/webpack.config.js');

module.exports = (env, argv) => {
    if (!env) {
        env = {};
    }

    env.root_path = __dirname;
    const config = webpackConfig(env, argv);

    return config;
};

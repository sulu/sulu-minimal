const HtmlWebpackPlugin = require('html-webpack-plugin');
const glob = require('glob');

const entries = glob.sync('./vendor/**/Resources/js/index.js');

module.exports = {
    entry: entries,
    output: {
        path: 'web',
        filename: '[name].bundle.js',
    },
    module: {
        loaders: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
                query: {
                    presets: ['react', 'es2015'],
                    plugins: [
                        'transform-decorators-legacy',
                        'transform-class-properties',
                    ],
                },
            },
        ],
    },
    plugins: [
        new HtmlWebpackPlugin({
            filename: 'admin.html'
        }),
    ],
};

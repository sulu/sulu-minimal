/* eslint-disable */
const path = require('path')

module.exports = ({ file, options, env }) => ({
    plugins: {
        'postcss-import': {
            root: path.resolve(__dirname, 'node_modules'),
            path: [path.resolve(__dirname, 'node_modules')],
        },
        'postcss-nested': {},
        'postcss-simple-vars': {},
        'postcss-calc': {},
        'postcss-hexrgba': {},
        'autoprefixer': {},
    },
});

// Import components
var webpack = require('webpack');
var autoprefixer = require('autoprefixer');
var path = require('path');

module.exports = {
    entry: [
        'webpack-hot-middleware/client?path=http://192.168.99.100:4000/__webpack_hmr', // enable hot reloading (should only be used for development)
        path.join(__dirname, '/app/index.js')
    ],
    output: {
        publicPath: 'http://192.168.99.100:4000/', // enable hot relaoding ( should only be used for development)
        path: path.join(__dirname, '/dist'),
        filename: 'bundle.js'
    },
    devtool: 'source-map',
    module: {
        loaders: [
            {
                test: /\.(ttf|eot|woff)(\?.*)?$/,
                loaders: ['url']
            },
            {
                test: /\.svg(\?.*)?$/,
                loaders: ['url', 'svgo']
            },
            {
                test: /\.scss$/,
                loaders: ['style', 'css', 'sass', 'postcss']
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loaders: ['babel']
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loaders: ['eslint']
            },
            {
                test: /\.html$/,
                loader: 'html'
            },
            {
                test: /\.json$/,
                loader: 'json'
            }
        ]
    },
    eslint: {
        parser: 'babel-eslint'
    },
    postcss: function() {
        return [autoprefixer];
    },
    plugins: [
        new webpack.optimize.OccurenceOrderPlugin(),
        new webpack.HotModuleReplacementPlugin(),
        new webpack.NoErrorsPlugin()
    ]
};

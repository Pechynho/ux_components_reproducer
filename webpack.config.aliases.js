/**
 * Aliases for require/import statements
 * To have auto-completion and file navigation in
 * PhpStorm point "webpack configuration file" setting
 * in "Languages & Frameworks > Javascript > Webpack" to this file
 */

const path = require('path');

module.exports = {
    resolve: {
        alias: {
            vendor: path.resolve(__dirname, 'vendor'),
            public: path.resolve(__dirname, 'public'),
            app: path.resolve(__dirname, 'assets'),
            components: path.resolve(__dirname, 'components'),
            project: path.resolve(__dirname),
        },
    },
};

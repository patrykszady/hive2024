const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            // screens: {
            //     print: {raw: 'print'},
            //     screen: {raw: 'screen'},
            // },
        },
    },

    plugins: [
        require('@tailwindcss/forms')
    ],
};

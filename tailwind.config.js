const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    purge: [
        './resources/views/**/*.blade.php',
        './storage/framework/views/*.php',
    ],

    theme: {
        colors: {
            current: 'currentColor',
            transparent: 'transparent',
            black: colors.black,
            gray: colors.warmGray,
            indigo: colors.indigo,
            red: colors.red,
            yellow: colors.amber,
            white: colors.white,
        },
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            }
        },
    },

    variants: {
        extend: {
            borderRadius: ['first', 'last'],
            opacity: ['disabled'],
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};

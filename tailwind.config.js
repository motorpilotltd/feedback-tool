import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'
import aspectRatio from '@tailwindcss/aspect-ratio'
import typography from '@tailwindcss/typography'
const colors = require('tailwindcss/colors')

module.exports = {
    mode: 'jit',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './resources/views/**/*.blade.php',
        './vendor/wireui/wireui/resources/**/*.blade.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/View/**/*.php',
    ],
    presets: [require('./vendor/wireui/wireui/tailwind.config.js')],
    theme: {
        extend: {
            colors: {
                gray: colors.neutral,
                'gray-background': '#f7f8fc',
                'blue-base': '#328af1',
                'blue-hover': '#2879bd',
                'yellow-base': '#ffc73c',
                'red-base': '#ec454f',
                'red-100': '#fee2e2',
                'green-base': '#1aab8b',
                'green-50': '#f0fdf4',
                'purple-base': '#8b60ed',
            },
            spacing: {
                22: '5.5rem',
                70: '17.5rem',
                76: '19rem',
                104: '25rem',
                128: '32rem',
                175: '43.75rem',
            },
            maxWidth: {
                custom: '68.5rem',
            },
            boxShadow: {
                card: '4px 4px 15px 0 rgba(36, 37, 38, 0.08)',
                dialog: '3px 4px 15px 0 rgba(36, 37, 38, 0.22)',
            },
            fontFamily: {
                sans: ['Open Sans', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                xxs: [
                    '0.625rem',
                    {
                        lineheight: '1rem',
                    },
                ],
            },
        },
    },
    variants: {
        extend: {
            opacity: ['disabled'],
        },
    },
    safelist: [
        {
            pattern: /(bg|border|text)-(amber)-(.+)/,
        },
    ],
    plugins: [forms, aspectRatio, typography],
}

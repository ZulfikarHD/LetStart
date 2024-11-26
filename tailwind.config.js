import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                appGreenLight: '#70BC2A',
                appGreenMedium: '#47981F',
                appGreenDark: '#2F5D1E',
                appBlueLight: '#52A7E8',
                appBlueMedium: '#1B66DA',
                appBlueDark: '#1351D0',
                appBlack: '#142312',
            },
        },
    },
    plugins: [
        // Plugin for removing scrollbar
        function({ addUtilities }) {
            addUtilities({
                '.no-scrollbar': {
                    '-ms-overflow-style': 'none',
                    'scrollbar-width': 'none',
                    '&::-webkit-scrollbar': {
                        display: 'none',
                    },
                },
            });
        },
    ],
};

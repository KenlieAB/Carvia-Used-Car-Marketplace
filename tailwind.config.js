import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                jakarta: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                navy: {
                    DEFAULT: '#0f1e36',
                    50:  '#e8edf4',
                    100: '#c5d0e3',
                    200: '#9fb0ce',
                    300: '#7890b9',
                    400: '#5b78aa',
                    500: '#3d609b',
                    600: '#345592',
                    700: '#294787',
                    800: '#1e397a',
                    900: '#0f1e36',
                },
            },
        },
    },

    plugins: [forms],
};

// In tailwind.config.js

const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        // ADD THIS 'extend' BLOCK
        extend: {
            colors: {
                'primary': {
                    '50': '#eef2ff',
                    '100': '#e0e7ff',
                    '200': '#c7d2fe',
                    '300': '#a5b4fc',
                    '400': '#818cf8',
                    '500': '#6366f1', // A nice Indigo
                    '600': '#4f46e5', // Our main Primary color
                    '700': '#4338ca',
                    '800': '#3730a3',
                    '900': '#312e81',
                },
                'success': '#10b981',
                'danger': '#ef4444',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                'upm': {
                    50: '#f2f9fd',
                    100: '#e3f0fb',
                    200: '#c2e2f5',
                    300: '#8bcbee',
                    400: '#4eb0e2',
                    500: '#2797d0',
                    600: '#1a81be',
                    700: '#15608f',
                    800: '#155277',
                    900: '#174563',
                    950: '#0f2c42',
                    'default': '#1a81be',
                },

            },
        },
    },
    plugins: [],
}


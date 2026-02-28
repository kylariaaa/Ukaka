/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                inter: ['Inter', 'sans-serif'],
            },
            colors: {
                orange: {
                    DEFAULT: '#FF8A4C',
                    dark: '#e6743b',
                    light: '#ffaa7f',
                },
            },
        },
    },
    plugins: [],
};

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/views/index.blade.php",
        "./public/src/js/.{js,ts,jsx,tsx}",
        "./public/src/css/input.css",
    ],
    theme: {
        extend: {
            screens: {
                "3xl": "1920px",
                "4xl": "2560px",
            },
        },
    },
    plugins: [],
};

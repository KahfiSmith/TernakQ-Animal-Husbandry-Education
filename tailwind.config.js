import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                inter: ["Inter", "sans-serif"],
            },
            colors: {
                cosmicLatte: "#FFF9E9",
                orangeCrayola: "#F97930",
                maize: "#FFC942",
                pewterBlue: "#8BBDB2",
                vodka: "#C3BBFC",
                congoPink: "#F98080",
                polishedPine: "#56A795",
            },
        },
    },

    plugins: [forms],
};

import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import ZapfHumanist from "./resources/fonts/ZapfHumanist/ZapfHumanist-Roman.otf";
import Avenir from "./resources/fonts/AvenirLTPro/AvenirLTPro-Roman.otf";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            colors: {
                "guinda-principal": "#9B2242",
                "guinda-secundario": "#651D32",
                "gris-cool": "#474C55",
                gris: "#727372",
                "gris-claro": "#B8B6AF",
                negro: "#130D0E",
            },
            fontFamily: {
                zapf: ["ZapfHumanist", "serif"],
                avenir: ["Avenir", "sans-serif"],
            },
            boxShadow: {
                guinda: "0 8px 32px rgba(155, 34, 66, 0.15)",
                "guinda-lg": "0 15px 50px rgba(155, 34, 66, 0.25)",
            },
            backgroundImage: {
                "gradient-guinda":
                    "linear-gradient(135deg, #9B2242 0%, #651D32 100%)",
            },
        },
    },
    // theme: {
    //     extend: {
    //         fontFamily: {
    //             sans: ["Figtree", ...defaultTheme.fontFamily.sans],
    //         },
    //     },
    // },

    plugins: [forms],
};

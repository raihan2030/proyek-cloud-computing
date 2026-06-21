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
            colors: {
                "on-primary-fixed": "#1a1c1c",
                "on-error": "#690005",
                "surface-container": "#201f1f",
                "inverse-on-surface": "#313030",
                "on-secondary": "#2f3037",
                "surface": "#141313",
                "on-error-container": "#ffdad6",
                "secondary-fixed-dim": "#c6c6cf",
                "secondary-container": "#45464e",
                "primary-fixed-dim": "#c6c6c7",
                "tertiary-container": "#e2e2e2",
                "surface-container-highest": "#353434",
                "secondary": "#c6c6cf",
                "secondary-fixed": "#e2e1eb",
                "error": "#ffb4ab",
                "surface-dim": "#141313",
                "on-primary": "#2f3131",
                "primary-container": "#e2e2e2",
                "surface-container-lowest": "#0e0e0e",
                "surface-variant": "#353434",
                "error-container": "#93000a",
                "tertiary-fixed": "#e2e2e2",
                "on-tertiary-fixed": "#1a1c1c",
                "outline-variant": "#444748",
                "tertiary": "#ffffff",
                "on-secondary-fixed": "#1a1b22",
                "on-tertiary-container": "#636565",
                "on-surface-variant": "#c4c7c8",
                "on-background": "#e5e2e1",
                "surface-bright": "#3a3939",
                "primary-fixed": "#e2e2e2",
                "tertiary-fixed-dim": "#c6c6c7",
                "on-secondary-fixed-variant": "#45464e",
                "on-secondary-container": "#b4b4bd",
                "surface-container-high": "#2a2a2a",
                "surface-container-low": "#1c1b1b",
                "primary": "#ffffff",
                "outline": "#8e9192",
                "on-tertiary-fixed-variant": "#454747",
                "inverse-primary": "#5d5f5f",
                "on-surface": "#e5e2e1",
                "on-primary-fixed-variant": "#454747",
                "background": "#141313",
                "on-tertiary": "#2f3131",
                "surface-tint": "#c6c6c7",
                "on-primary-container": "#636565",
                "inverse-surface": "#e5e2e1"
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            borderRadius: {
                "DEFAULT": "0.25rem",
                "lg": "0.5rem",
                "xl": "0.75rem",
                "full": "9999px"
            },
            spacing: {
                "margin-desktop": "2rem",
                "margin-mobile": "1rem",
                "lg": "1.5rem",
                "xs": "0.25rem",
                "gutter": "1rem",
                "xl": "2.5rem",
                "md": "1rem",
                "base": "4px",
                "sm": "0.5rem"
            }
        },
    },

    plugins: [forms],
};


import {Config} from 'tailwindcss';
import defaultTheme from 'tailwindcss/defaultTheme';

export default {
    content: [
        './templates/**/*.html.twig',
        './assets/**/*.{js, jsx, ts, tsx}',
        './components/**/*.html.twig',
        './components/**/*.{js, jsx, ts, tsx}',
    ],
    safelist: [
        '!mb-0',
        '!mb-3',
    ],
    darkMode: 'class',
    theme: {
        fontFamily: {
            body: [
                'Inter',
                'ui-sans-serif',
                'system-ui',
            ],
            sans: [
                'Inter',
                'ui-sans-serif',
                'system-ui',
            ],
        },
        screens: {
            'max-sm': {
                max: '640px',
            },
            'max-md': {
                max: '768px',
            },
            'max-lg': {
                max: '1024px',
            },
            'max-xl': {
                max: '1280px',
            },
            ...defaultTheme.screens,
        },
        extend: {
            colors: {
                brand: {
                    DEFAULT: 'rgb(var(--color-brand) / <alpha-value>)',
                    active: 'rgb(var(--color-brand-active) / <alpha-value>)',
                    disabled: 'rgb(var(--color-brand-disabled) / <alpha-value>)',
                    ring: 'rgb(var(--color-brand-ring) / <alpha-value>)',
                    accent: 'rgb(var(--color-brand-accent) / <alpha-value>)',
                    inverse: 'rgb(var(--color-brand-inverse) / <alpha-value>)',
                },
                danger: {
                    DEFAULT: 'rgb(var(--color-danger) / <alpha-value>)',
                    active: 'rgb(var(--color-danger-active) / <alpha-value>)',
                    disabled: 'rgb(var(--color-danger-disabled) / <alpha-value>)',
                    ring: 'rgb(var(--color-danger-ring) / <alpha-value>)',
                    accent: 'rgb(var(--color-danger-accent) / <alpha-value>)',
                    inverse: 'rgb(var(--color-danger-inverse) / <alpha-value>)',
                },
            },
            borderColor: {
                main: {
                    DEFAULT: 'rgb(var(--color-border-main) / <alpha-value>)',
                },
            },
            backgroundColor: {
                body: {
                    DEFAULT: 'rgb(var(--color-background-body) / <alpha-value>)',
                },
                input: {
                    DEFAULT: 'rgb(var(--color-background-input) / <alpha-value>)',
                    disabled: 'rgb(var(--color-background-input-disabled) / <alpha-value>)',
                },
                neutral: {
                    1: 'rgb(var(--color-background-neutral-1) / <alpha-value>)',
                    2: 'rgb(var(--color-background-neutral-2) / <alpha-value>)',
                },
            },
            textColor: {
                main: {
                    DEFAULT: 'rgb(var(--color-text-main) / <alpha-value>)',
                },
                secondary: {
                    DEFAULT: 'rgb(var(--color-text-secondary) / <alpha-value>)',
                },
                disabled: {
                    DEFAULT: 'rgb(var(--color-text-disabled) / <alpha-value>)',
                },
                brand: {
                    DEFAULT: 'rgb(var(--color-brand-text) / <alpha-value>)',
                },
                danger: {
                    DEFAULT: 'rgb(var(--color-danger-text) / <alpha-value>)',
                },
            },
            placeholderColor: {
                main: {
                    DEFAULT: 'rgb(var(--color-placeholder-main) / <alpha-value>)',
                },
                brand: {
                    DEFAULT: 'rgb(var(--color-brand-text) / <alpha-value>)',
                },
                danger: {
                    DEFAULT: 'rgb(var(--color-danger-text) / <alpha-value>)',
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/container-queries'),
        require('tw-bootstrap-grid-optimizer'),
    ],
} satisfies Config;



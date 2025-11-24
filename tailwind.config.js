import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './node_modules/flowbite/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'neutral-primary-soft': '#fafaf9',
                'neutral-primary-medium': '#f5f5f4',
                'neutral-secondary-soft': '#e7e5e4',
                'neutral-secondary-medium': '#d6d3d1',
                'neutral-tertiary': '#a8a29e',
                'neutral-tertiary-medium': '#78716c',
                'default': '#e5e7eb',
                'default-medium': '#d1d5db',
                'heading': '#111827',
                'body': '#6b7280',
                'fg-disabled': '#9ca3af',
                'fg-brand': '#3b82f6',
                'fg-danger-strong': '#dc2626',
                'danger-soft': '#fee2e2',
                'danger-subtle': '#fecaca',
            },
            borderRadius: {
                'base': '0.375rem',
                'sm': '0.25rem',
            },
            borderWidth: {
                '1': '1px',
            },
            spacing: {
                '4.5': '1.125rem',
                '14.5': '3.625rem',
            },
        },
    },

    plugins: [
        forms, 
        typography,
        require('flowbite/plugin')
    ],
};

import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
		'./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
		 './storage/framework/views/*.php',
		 './resources/**/*.blade.php',
		 './resources/**/*.js',
		 './resources/**/*.vue',
		 "./vendor/robsontenorio/mary/src/View/Components/**/*.php"
	],

    darkMode: 'class',
    daisyui: {
        themes: [
          {
              light: {
                ...require("daisyui/src/theming/themes")["light"],
              "primary": "#002855",
              "secondary": "#f6d860",
              "accent": "#37cdbe",
              "neutral": "#3d4451",
              "base-100": "#ffffff",
            },
          },
        ],
      },
    theme: {


        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [
		require("daisyui")
	],
};

/** @type {import('tailwindcss').Config} */

export default {
  content: ["./src/**/*.{html,js,svelte}", "./src/**/*.{html,js,svelte,ts}"],
  darkMode: 'selector',
  theme: {
    screens: {
      'sm': '640px', 'md': '768px', 'lg': '1024px', 'xl': '1280px', '2xl': '1536px',
    },
    extend: {
      colors: {
        button: '#ffffff',
        header: '#000000', // in app.css components
      },
      backgroundColor: {
        button: '#d97706',
        header: '#99F6E4',
      },
      borderColor: {
        header: '#115E59',
      },
      fontFamily: {
        rgz: [ 'Nunito Sans', 'sans-serif' ],
        ///*    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; */
      },
      spacing: {
        '104': '26rem',
        '112': '28rem',
        '128': '32rem',
        '144': '36rem',
        '160': '40rem',
        '176': '44rem',
        '192': '48rem',
        '228': '56rem',
        '244': '60rem',
        '256': '64rem',
        '272': '68rem',
        '288': '72rem',
        '320': '80rem',
        '384': '96rem',
        '512': '128rem'
      },
    },
  },
  plugins: [],
}

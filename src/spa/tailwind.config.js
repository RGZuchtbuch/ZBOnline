// https://dev.to/matebek/simplest-way-to-set-up-svelte-with-tailwind-css-41bn
module.exports = {
  content: ['../backend/public/index.html', './src/**/*.svelte'],
  theme: {
    extend: {
      spacing: {
        '128': '32rem',
        '144': '36rem',
        '160': '40rem',
        '176': '44rem',
        '192': '48rem',
        '228': '56rem',
        '244': '60rem',
        '256': '64rem',
        '272': '68rem',
        '288': '72rem'
      }
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
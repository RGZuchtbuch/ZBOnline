const config = {
  content: ["./src/**/*.{html,js,svelte,ts}"],

  theme: {
    extend: {
      colors: {
        'button': '#4ade80',
        'header': '#bbf7d0',
      },
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
      },
    },
  },

  plugins: [],
};

module.exports = config;

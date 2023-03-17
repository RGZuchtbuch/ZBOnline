const config = {
  content: ["./src/**/*.{html,js,svelte,ts}"],

  theme: {
    extend: {
      colors: {
        'alert' : '#FA917B',
        'button': '#2BAD7A77',
        'header': '#57FABA',
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
        '288': '72rem',
        '384': '96rem'
      },
      fontSize: {
        '2xs': '0.5rem',
      },
    },
  },

  plugins: [],
};

module.exports = config;

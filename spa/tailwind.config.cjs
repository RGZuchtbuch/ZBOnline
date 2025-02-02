const config = {
  content: ["./src/**/*.{html,js,svelte,ts}"],

  theme: {
    extend: {
      colors: {
        'alert' : '#fc5226',
        'button': '#fc5226',
        'header': '#1dbab4',
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
        '320': '80rem', // 64+256
        '384': '96rem',
        '512': '128rem'
      },
      fontSize: {
        '2xs': '0.5rem',
        '3xs': '0.4rem',
      },
    },
  },

  plugins: [],
};

module.exports = config;

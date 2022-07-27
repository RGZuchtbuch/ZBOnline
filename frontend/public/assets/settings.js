// use for caching network gets
const settings = {
    api : {
        root: 'http://localhost:8888/',
    },
    cache : {
        TIMEOUT : 5000 // should be 30 mins = 30 * 60000 = 1800000
    },
    date : {
        min:new Date( 1850,0,1 ), // before start of all this 1850-01-01,
        max:new Date( new Date().getFullYear() + 1,11,31 ) // next year
    }

}

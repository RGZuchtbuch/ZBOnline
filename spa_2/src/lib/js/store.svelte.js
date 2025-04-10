import {writable} from 'svelte/store';

//export const articles   = writable( null );
//export const article    = writable( null );
// export const federation = writable( null );
// export const standard   = writable( null );
// export const title      = writable( null ); // TODO,  needed ?
// export const menu = writable( { trail:[], options:[] } );
// export const url = writable( null );
//export const user = writable( null );

export const moderator = writable( null );
export const districts = writable( null );
export const district  = writable( null );

class Report {
    chart = $state( null );
    map   = $state( null );
    table = $state( null );
    trend = $state( null );
}

class Store {

    federation = $state( null );
    standard   = $state( null );
    user       = $state( null );


    title      = $state(null );
    menu       = $state(null );

    aocSection = $state( { id:9999, name:'AOC-Klasse', breeds:[] } );
    groups  = ['I', 'II', 'III'];
}
export default new Store();

const report = new Report();

// export default {
//     articles:articles,
//     article:article,
//
//     district:district,
//     districts:districts,
//     federation:federation,
//     menu:menu,
//     moderator:moderator,
//     report:report,
//     standard:standard,
//     title:title,
//     url:url,
//     user:user,
// }


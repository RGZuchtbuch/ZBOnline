/**
 * ctx store for each layout/page.svelte to store loaded objects in for children to use.
 * this is accompanied by the dirty flags, that triggers a list of object as an object has been changed. This is substituting the invalidate sveltekit load option, that did not work for this app, too little control.
 */

// const initialMenuState = { // having last href for menu item
//     '/article'   : '/article',
//     '/federation': '/federation',
//     '/standard'  : '/standard',
//     '/report'    : '/report',
//     '/tool'      : '/tool',
//     '/breeder'   : '/breeder',
//     '/moderator' : '/moderator',
//     '/admin'     : '/admin',
// }

class Context {
    //args       = $state( null);
    article    = $state(null);
    articles   = $state(null);
    breed    = $state(null);
    breeds   = $state(null);
    breeder    = $state(null);
    breeders   = $state(null);
    district   = $state(null);
    districts  = $state(null);
    federation = $state(null);
    header     = $state( { title:null, menu:null } );
    pair       = $state(null);
    pairs      = $state(null);
    report     = $state(null);
    result     = $state(null);
    results    = $state(null);
    resultsEdit= $state(null);
    section    = $state(null);
    sections   = $state(null);
    standard   = $state(null); // whole standard with sections, breeds and colors
    user       = $state(null);
    year       = $state(null);

    // for managing page title and menu with crumbs
    title = $state(null);
    menu = $state(null);
    submenu = $state(null);
    crumbs = $state(null);

    initialMenustate = { // to allow for resetting on logout.
        '/article'   : '/article',
        '/federation': '/federation',
        '/standard'  : '/standard',
        '/report'    : '/report',
        '/tool'      : '/tool',
        '/breeder'   : '/breeder',
        '/moderator' : '/moderator',
        '/admin'     : '/admin',
    };
    menustate = $state( { ... this.initialMenustate } );

    dialog = $state( null );
}

/**
 * Hold constants and predefined objects ( not changed )
 */
class Config {
//    aocSection = $state( { id:9999, name:'AOC-Klasse', breeds:[] } ); // TODO obsolete
    groups  = ['I', 'II', 'III'];
    broodGroups = [ 1, 2, 3, 4 ];
    pigeons = 5; // note, not for subsections
    ringColors = [
        // start at 2022, then repeat every 6 years
        // get color by index = year % 6;
        { name:'schwarz', color:'#000' },
        { name:'gelb', color:'#FF0' },
        { name:'blau', color:'#00F' },
        { name:'grün', color:'#0F0' },
        { name:'grau', color:'#444' },
        { name:'weiß', color:'#FFF' },
    ];
    fadeIn = 500; // for fade in duration
    // rootSections defined in js/model/standard.js
}

class Dirty { // flag dirty, for forcing reloading in +page
    article  = $state( 1 );
    articles = $state( 1 );

    breeder  = $state( 1 );
    breeders  = $state( 1 );

    district  = $state( 1 );
    districts  = $state( 1 );

    federation = $state( 1 );

    pair  = $state( 1 );
    pairs  = $state( 1 );

    report  = $state( 1 );

    result  = $state( 1 );
    results  = $state( 1 ); // list of entered results
    resultsEdit  = $state( 1 ); // list of editable results

    standard = $state( 1);
}

export let ctx = new Context(); // loaded stuff
//export let store = new Store(); // obsolete
export let cfg = new Config(); // predefines stuff

export let dirty = new Dirty(); // mark ctx items to be reloaded

// experiment
// const usr = $state( { id:1, name:'Eelco' } )
// export const test = {
//     usr: usr,
// }
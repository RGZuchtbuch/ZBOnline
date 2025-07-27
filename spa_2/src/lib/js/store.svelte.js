// ctx store for each layout.svelte to store loaded objects in.
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
    resultsEdit    = $state(null);
    section   = $state(null);
    sections   = $state(null);
    standard   = $state(null); // whole standard with sections, breeds and colors
    user       = $state(null);

    crumbs   = $state([]);
}


class Config {

    // federation = $state(null);
    // standard   = $state(null); // whole standard with sections, breeds and colors

    aocSection = $state( { id:9999, name:'AOC-Klasse', breeds:[] } );
    groups  = ['I', 'II', 'III'];
}

class Dirty { // flag dirty, for reloading
    article  = $state( true );
    articles = $state( true );

    breeder  = $state( true );
    breeders  = $state( true );

    district  = $state( true );
    districts  = $state( true );

    federation = $state( true );

    pair  = $state( true );
    pairs  = $state( true );

    report  = $state( true );
    result  = $state( true );
    results  = $state( true );
    resultsEdit  = $state( true );

    standard = $state( true );

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
// ctx store for each layout.svelte to store loaded objects in.
class Context {
    args       = $state( null);
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
    section   = $state(null);
    sections   = $state(null);
    standard   = $state(null); // whole standard with sections, breeds and colors
    user       = $state(null);
}


class Config {

    // federation = $state(null);
    // standard   = $state(null); // whole standard with sections, breeds and colors

    aocSection = $state( { id:9999, name:'AOC-Klasse', breeds:[] } );
    groups  = ['I', 'II', 'III'];
}

class Store {

//    breeder    = $state(null);
//    breeders   = $state(null);
    federation = $state(null);
//    header     = $state( { title:null, menu:null } );
//    pair       = $state(null);
//    pairs      = $state(null);
//    report     = $state(null);
//    result     = $state(null);
//    results    = $state(null);
    standard   = $state(null); // whole standard with sections, breeds and colors

    aocSection = $state( { id:9999, name:'AOC-Klasse', breeds:[] } );
    groups  = ['I', 'II', 'III'];
}

export let ctx = new Context();
export let store = new Store(); // obsolete
export let cfg = new Config();

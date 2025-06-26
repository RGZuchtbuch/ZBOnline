// ctx store for each layout.svelte to store loaded objects in.
class Context {
    article    = $state(null);
    articles   = $state(null);
    breeder    = $state(null);
    breeders   = $state(null);
    federation = $state(null);
    header     = $state( { title:null, menu:null } );
    pair       = $state(null);
    pairs      = $state(null);
    report     = $state(null);
    result     = $state(null);
    results    = $state(null);
    standard   = $state(null); // whole standard with sections, breeds and colors
    user       = $state(null);

    aocSection = $state( { id:9999, name:'AOC-Klasse', breeds:[] } );
    groups  = ['I', 'II', 'III'];
}

export let ctx = new Context();


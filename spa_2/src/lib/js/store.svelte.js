import {writable} from 'svelte/store';

class AppState {
    title = $state( null );
    menu = $state({
        trial:null,
        options:null
    } );

    user = $state( { id:1, firstname:'Eelco', infix:null, lastname:'Jannink', moderates:[ 2, 6 ] });

    articles = $state( null );
    standard = $state( null );
    districts = $state( null );
    breeders = $state( null );
    pairs = $state( null );

    district = $state( null );
    breeder = $state( null );
    pair = $state( null );

    //rootSections = $state( rootSections );
}

// const rootSections = [
//     { id:3,  name:'Groß u. Wassergeflügel', breeds:[] },
//     { id:11, name:'Hühner Groß', breeds:[] },
//     { id:12, name:'Zwerghühner', breeds:[] },
//     { id:13, name:'LegeWachteln', breeds:[] },
//     { id:5,  name:'Tauben', breeds:[] },
//     { id:6,  name:'Ziergeflügel', breeds:[] },
// ];

export let app = new AppState();




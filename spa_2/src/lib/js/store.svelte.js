//import {writable} from 'svelte/store';

class Store {
    page = new PageStore();
    data = new DataStore();
}

class PageStore {
    title = $state( null );
    menu = $state({
        trial:null,
        options:null
    } );
}

class DataStore {
    // set on login, reset to null on logout, dummy data for now
    user = $state( { id:1, firstname:'Eelco', infix:null, lastname:'Jannink', moderates:[ 2, 6 ] });

    // pre loaded
    federation = $state( null ); // district tree and districts by id for lookup
    standard = $state( null ); // standard tree and sections, breeds, colors by id for lookup

    // use in route tree
    article = $state( null );
    articles = $state( null );

    breeder = $state( null );
    breeders = $state( null );

    district = $state( null );
    districts = $state( null );

    pair = $state( null );
    pairs = $state( null );

    result = $state( null );
    results = $state( null );
}



const store = new Store()


export default store;
export const page = store.page;
export const data = store.data;
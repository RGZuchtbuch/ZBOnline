//import {writable} from 'svelte/store';

class Store {
    // set on login, reset to null on logout, dummy data for now
    federation = $state( null );
    results    = $state( null );
    standard   = $state( null );
    user       = $state( null );
    year    = $state( new Date().getFullYear() );
}
const store = new Store()
export default store;

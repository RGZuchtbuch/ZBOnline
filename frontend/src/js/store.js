import { writable } from 'svelte/store';

// usefull
export const groups = writable( ['I', 'II', 'III' ] );
export const sexes = writable( ['1.0', '0.1' ] );

// state
export const user = writable( null ); // keeping loggedin user
//export const district = writable( null ); // current districtexport
//export const breeder = writable( null ); // current breeder
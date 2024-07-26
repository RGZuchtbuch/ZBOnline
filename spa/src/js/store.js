import { writable } from 'svelte/store';


// usefull
export const groups = writable( ['I', 'II', 'III' ] );
export const sexes = writable( ['1.0', '0.1' ] );

// roles state
export const admin = { district:writable( null ), breeder:writable( null ) }; // has district and breeder to work on
export const breeder = { breeder:writable( null ) }; // has district and breeder to work on;
export const moderator = { district:writable( null ), breeder:writable( null ) }; // has district and breeder to work on

// state
// doupt on next two... how to keep track on url change
//export const breeder= writable( null ); // breeder currently worked on
//export const district= writable( null ); // district currently worked on

export const user = writable( null ); // keeping loggedin user

export const standard = writable( null ); // should load section geflügel as root

export const broodGroups = writable( [ {id:1, name:'I' }, {id:2, name:'II' }, {id:3, name:'III' }, {id:4, name:'IV' } ] );




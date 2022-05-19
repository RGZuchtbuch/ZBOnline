import { writable } from 'svelte/store';


const tester = {
    id: 10,
    name: 'Eelco Jannink',
    breeder: {
        id: 1054,
        club: 'Nordhorn',
        district: {
            id: 1,
            name: 'KV Emsland Grafschaft Bentheim',
        },
    },
    moderator: {
        districts: [ 1,2,3,4 ],
    }
    //admin: {}
}

export const user = writable( null );
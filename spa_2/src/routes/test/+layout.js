import { error } from '@sveltejs/kit';

/** @type {import('./$types').PageLoad} */
export function load({ params }) {
    console.log( 'Test layout.js Load' );
    return {
        test: {
            id:7,
            name:'Test',
        }
    };
//	error(404, 'Not found');
}
import {writable} from 'svelte/store';

export const articles   = writable( null );
export const article    = writable( null );
export const federation = writable( null );
export const standard   = writable( null );
export const title      = writable( null ); // TODO,  needed ?
export const menu = writable( { trail:[], options:[] } );
export const url = writable( null );
export const user = writable( null );

export const moderator = writable( null );
export const districts = writable( null );
export const district  = writable( null );

export const reports = {
    chart: writable( null ),
    map  : writable( null ),
    trend: writable( null ),
};

export default {
    articles:articles,
    article:article,
    district:district,
    districts:districts,
    federation:federation,
    menu:menu,
    moderator:moderator,
    reports:reports,
    standard:standard,
    title:title,
    url:url,
    user:user,
}


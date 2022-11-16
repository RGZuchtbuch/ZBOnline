import jwt_decode from 'jwt-decode';
import { user } from './store.js'
import { clone, reportTpl } from './template.js';

//uses constants from js/setting.js { settings.cache.TIMEOUT, settings.api.root }

const delay = 250;

let cache = {
    promises: {} // url -> promise, time
};

let token = window.sessionStorage.getItem( 'token' );

if( token ) {
    user.set(jwt_decode(token).user); // user or null
}


export default {
    user: {
        credentials: (email, password ) => {
            return post('api/credentials', {email: email, password: password}).then( response => {
                if( response ) {
                    token = response.token;
                    window.sessionStorage.setItem( 'token', token );
                    user.set(jwt_decode(token).user); // user or null
                    return true; // success
                }
                return false;
            });
        },
    },

    breed: {
        get: (id) => get( 'api/breed/'+id ),
        colors : {
            get: ( breedId ) => get( `api/breed/${breedId}/colors`)
        },
    },

    breeder: {
        get: (breederId) => get( 'api/breeder/'+breederId ),
        reports: {
            get: (breederId) => get( 'api/breeder/'+breederId+'/reports' ),
        },
        results: {
            get: (breederId) => get( 'api/breeder/'+breederId+'/results' ),
        },
//        getYears: (id) => get( 'api/breeder/'+id+'/years' ),
    },

    color: {
        get: ( id ) => get( 'api/color/'+id ),
    },

    district: {
        get: ( districtId ) => {
            console.log( 'api getDistrict', districtId );
            return get( 'api/district/'+districtId );
        },
        new: ( parentId ) => {
            console.log( 'api newDistrict' );
            return new Promise( ( resolve ) => {
                // TODO, remember to delete cache for parent district
                clear( 'api/district/'+parentId );
                resolve( { id:0, parent:parentId, name:null, fullname:null, short:null, coordinates:null, children:[], moderators:[] } );
            })
        },
        post: ( district ) => { // insert
            console.log( 'api postDistrict' );
            return post( 'api/district', district );
        },
        put: ( district ) => { // updating
            console.log( 'api postDistrict' );
            return put( 'api/district/'+district.id, district );
        },
        delete: ( districtId ) => {
            console.log( 'api deleteDistrict' );
            //return del( 'api/district/'+districtId ); // TODO delete or better disable !
        },

        tree: (parentId) => {
            return get('api/district/'+parentId+'/tree');
        },

        breeders: {
            get: (districtId) => {
                return get( 'api/district/'+districtId+'/breeders');
            }
        },

        results: {
            full: {
                get: (districtId, sectionId, year, group) => get('api/district/' + districtId + '/section/' + sectionId + '/year/' + year + '/group/' + group + '/results/full')
            },
        }
    },
    groups: {
        get: () => {
            console.log( 'api getGroups' );
            return new Promise( ( resolve ) => {
//                clear( 'api/district/'+parentId );
                resolve( { groups:['I', 'II', 'III' ] } );
            })
        }
    },
    moderator: {
        new: (districtId) => {
            console.log('api new moderator');
            let moderatorPromise = Promise.resolve({moderator: {id: 0, district: districtId}});
            let districtPromise = get('api/district/' + districtId);            ;
            let candidatesPromise = get('/api/users');

            return Promise.all([moderatorPromise, districtPromise, candidatesPromise])
                .then(responses => {
                    let moderator = responses[0];
                    moderator.district = responses[1].district;
                    moderator.users = responses[2].users;
                    clear('api/district/' + districtId);
                    return moderator;
                });
        },
        post: ( districtId, moderatorId ) => {
            let data = { user:moderatorId, district:districtId };
            clear('api/district/' + districtId);
            return post('api/moderator', data );
        },
        delete: ( districtId, moderatorId ) => {
            let data = { user:moderatorId, district:districtId };
            clear('api/district/' + districtId);
            return del('api/moderator', data );
        },

        districts: ( moderatorId ) => {
            return get( 'api/moderator/'+moderatorId+'/districts');
        }
    },

    report: {
        'new': ( districtId, breederId ) => {
            console.log('api new Report for ', districtId, breederId );
            const report = clone( reportTpl );
            report.breederId = breederId;
            report.districtId = districtId;
            return Promise.resolve({
                report: report
            });
        },
        get: (id) => get('api/report/' + id),
        post: (report) => {
            // TODO adjust cache
            return post( 'api/report', report );
        },
        put: (report) => {
            // TODO cache
            return put( 'api/report', report );
        },
        delete: ( id ) => del( 'api/report/'+id ),
    },

    result: {
        get: ( id ) => get( 'api/result/'+id ),
        post: ( result ) => post( 'api/result', result ), // does insert or replace based on id ( null )
        put: ( result ) => put( 'api/result', result ),
        delete: ( id ) => del( 'api/result/'+id ),

        breed: {
            get: ( breedId, districtId, year, group ) => get( 'api/result/breed/'+breedId+'/district/'+districtId+'/year/'+year+'/group/'+group+'/results'),
        },
        breeds: {
            get: ( districtId, sectionId, year, group ) =>
                get( 'api/result/breeds/district/'+districtId+'/section/'+sectionId+'/year/'+year+'/group/'+group ),
        },
    },

    section: {
        get: ( id ) => get( 'api/section/'+id ),
        children: {
            get: ( parentId ) => get( 'api/section/'+parentId+'/children')
        },
        getTree: ( parentId ) => get( 'api/section/'+parentId+'/tree'),
        breeds: {
            get: (sectionId) => get('api/section/' + sectionId + '/breeds'),

        }
    },
}




/**
 * clears item from cache if older than cacheTimout
 */
setInterval(  () => {
    let now = new Date().getTime(); // in ms
    const toDelete = []; // collecte old, avoiding deleting while iterating
    for( const url in cache ) { // check per url->promise and collect before delete
        let promise = cache[ url ];
        const time = promise.time;
        if( time < now - settings.cache.TIMEOUT ) {
            toDelete.push( url );
        }
    }
    for( const url of toDelete ) { // delete collected from cache
        console.log( 'Auto clear cache for ', url );
        delete cache[ url ];
    }
}, settings.cache.TIMEOUT )

function clear( url ) {
    let promise = cache[ url ];
    if( promise ) {
        console.log( 'Clearing cache for ', url );
    } else {
        console.log( 'NOT Clearing cache for', url );
    }
    delete cache[ url ];
}

function getHeaders() {
    let headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
    if( token ) {
        headers[ 'Authorization'] = 'Bearer '+token;
    }
    return headers;
}

async function get( url ) {
    let cached = cache[ url ];
    let now = new Date().getTime(); // in ms
    if( cached && cached.time > now-settings.cache.TIMEOUT ) { // fresh enough
        console.log('Cache', url );
        return cached.promise;
    } else {
        let options = {
            method: 'GET',
            headers: getHeaders()
        }
        console.log('new get', url);
        let promise = fetch( settings.api.root+url, options)
            .then( response => {
                if( response.ok ) {
                    const json = response.json();
                    console.log( 'Got response ', json );
                    return json;
                }
                throw response;
            });
        cache[url] = {promise: promise, time: now};
        return promise;
    }
}

async function post( url, data ) {
    let options = {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify( data ),
    }
    console.log( 'POST', url );
    return fetch( settings.api.root+url, options )
        .then( response => {
            //console.log( 'POST', response );
            if( response.ok ) {
                console.log( '  POST', 'ok' );
                return response.json();
            }
            throw response;
        } );
}

async function put( url, data ) {
    let options = {
        method: 'PUT',
        headers: getHeaders(),
        body: JSON.stringify( data ),
    }
    console.log( 'PUT', url, data );
    return fetch( settings.api.root+url, options )
        .then( response => {
            if( response.ok ) {
                return response.json();
            }
            throw response;
        })
}

async function del( url, data ) {
    let options = {
        method: 'DELETE',
        headers: getHeaders(),
        body: JSON.stringify( data ),
    }
    console.log('DELETE', url, data );
    return fetch( settings.api.root+url, options)
        .then( response => {
            if( response.ok ) {
                return response.json();
            }
            throw response;
        });
}




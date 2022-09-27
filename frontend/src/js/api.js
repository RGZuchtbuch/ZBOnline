import jwt_decode from 'jwt-decode';
import { user } from './store.js'

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
        authorize: (email, password ) => {
            return post('/api/token', {email: email, password: password}).then( response => {
                if( response ) {
                    token = response;
                    window.sessionStorage.setItem( 'token', token );
                    return jwt_decode( token ).user;
                }
                return null;
            });
        },
    },

    breed: {
        get: (id) => get( 'api/breed/'+id ),
        getColors: ( breedId ) => get( `api/breed/${breedId}/colors`),
    },

    breeder: {
        get: (id) => get( 'api/breeder/'+id ),
        getReports: (id) => get( 'api/breeder/'+id+'/reports' ),
        getResults: (id) => get( 'api/breeder/'+id+'/results' ),
        getYears: (id) => get( 'api/breeder/'+id+'/years' ),
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

        getBreeders: (districtId) => {
            return get( 'api/district/'+districtId+'/breeders');
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
                resolve( ['I', 'II', 'III' ] );
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
        'new': (breederId) => {
            console.log('api new Report for ', breederId);
            let breederPromise = get('api/breeder/' + breederId);
            let reportPromise = Promise.resolve({
                id: 0,
                breederId: breederId,
                year: 2022, name: 'Test', group: 1, paired: null,
                breed: { sectionId: 4, breedId: 1024, colorId: 8543 },
                parents: [
                    {
                        sex: '1.0',
                        ring: {country: 'D', year: new Date().getFullYear(), code: 'AA 000'},
                        score: null,
                        parents: {id: null, breeder: null, year: 2021, name: null}
                    },
                    {
                        sex: '0.1',
                        ring: {country: 'D', year: new Date().getFullYear(), code: 'AZ 999'},
                        score: null,
                        parents: {id: null, breeder: null, year: 2021, name: null}
                    },
                ],
                lay: {start: '2022-01-01', until: '2022-04-01', eggs: 50},
                broods: [
                    {
                        start: null,
                        eggs: null,
                        fertile: null,
                        hatched: null,
                        ringed: null,
                        chicks: [{country: 'D', year: new Date().getFullYear(), code: 'AA 100'}, {
                            country: 'D',
                            year: new Date().getFullYear(),
                            code: 'AA 101'
                        }]
                    },
                    {
                        start: null,
                        eggs: null,
                        fertile: null,
                        hatched: null,
                        ringed: null,
                        chicks: [{country: 'D', year: new Date().getFullYear(), code: 'AA 100'}, {
                            country: 'D',
                            year: new Date().getFullYear(),
                            code: 'AA 101'
                        }]
                    }
                ],
                show: {
                    scores: {89: null, 90: null, 91: null, 92: null, 93: null, 94: null, 95: null, 96: null, 97: null}
                },
                notes: null,
                //result must be deducted.
            });
            return Promise.all([reportPromise, breederPromise])
                .then(responses => {
                    let report = responses[0];
                    report.breeder = responses[1];
                    // clear caches
                    return report;
                });
        },
        get: (id) => get('api/pair/' + id),
        post: (report) => {
            // TODO adjust cache
            return post( 'api/pair/', report );
        },
        put: (report) => {
            // TODO cache
            return put( 'api/pair/'+report.id, report );
        },
    },
    result: {
        get: ( id ) => get( 'api/result/'+id ),
        post: ( result ) => post( 'api/result', result ),
        put: ( result ) => put( 'api/result/'+id, result ),
        delete: ( id ) => del( 'api/result'+id ),

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
        console.log('t', url);
        let promise = fetch( settings.api.root+url, options)
            .then( response => {
                if( response.ok ) {
                    return response.json();
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




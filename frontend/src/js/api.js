import jwt_decode from 'jwt-decode';
import { user } from './store.js'


//uses constants from js/setting.js { settings.cache.TIMEOUT, settings.api.root }

let cache = {
    promises: {} // url -> promise, time
};

let token = window.sessionStorage.getItem( 'token' );
// eelco
if( token !== null ) { // mind, could be "null" text as well
    const decoded = jwt_decode(token);
    if( decoded.exp * 1000 > Date.now() ) {
        decoded.user.exp = decoded.exp;
        user.set(decoded.user); // user from token or null
    } else {
        token = null;
        user.set( null ); // expired
    }
} else {
    user.set( null );
}

export default {
    user: {
        login: (email, password ) => {
            return post('api/credentials', {email: email, password: password}).then( response => {
                if( response ) {
                    token = response.token;
                    window.sessionStorage.setItem( 'token', token );
                    const decToken = jwt_decode(token);
                    decToken.user.exp = decToken.exp;
                    console.log( 'Token', decToken );
                    console.log( 'Expired', new Date( decToken.exp * 1000 ) );
                    user.set( decToken.user ); // user or null
                    return { success:true }; // success
                }
                return { success: false };
            }).catch( response => {
                console.log( 'oops', response );
                return { success: false };
            });

        },
        logout: () => {
            token = null;
            window.sessionStorage.clear();
            user.set( null ); // user or null
            return true; // always success
        }
    },

    breed: {
        get: (id) => get( 'api/breed/'+id ),
        colors : {
            get: ( breedId ) => get( `api/breed/${breedId}/colors`)
        },
    },

    breeder: {
        get: ( breederId ) => get( 'api/breeder/'+breederId ),
        new: () => { // id being null
            console.log( 'api newDistrict' );
            return new Promise( ( resolve ) => {
                // TODO, remember to delete cache for parent district
                resolve( { id:0, parent:parentId, name:null, fullname:null, short:null, coordinates:null, children:[], moderators:[] } );
            })
        },
        reports: {
            get: (breederId) => get( 'api/breeder/'+breederId+'/reports' ),
        },
        results: {
            get: (breederId) => get( 'api/breeder/'+breederId+'/results' ),
        },
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
        children: {
            get: (districtId) => {
                return get( 'api/district/'+districtId+'/children' );
            }
        },

        results: {
            //get: (districtId, sectionId, year, group) => get('api/district/' + districtId + '/section/' + sectionId + '/year/' + year + '/group/' + group + '/results/full'),
            get: (districtId, year) => {
                return get( 'api/district/'+districtId+'/results/'+year );
            },
        },

        root: {
            get: ( rootId ) => {
                return get( 'api/district/'+rootId+'/root');
            }
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

    map: {
        color: {
            get: ( year, colorId ) =>
                get( 'api/results/districts?year='+year+'&color='+colorId ),
        },
        breed: {
            get: ( year, breedId ) =>
                get( 'api/results/districts?year='+year+'&breed='+breedId ),
        },
        section: {
            get: ( year, sectionId ) =>
                get( 'api/results/districts?year='+year+'&section='+sectionId ),
        },
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
//        districts: ( moderatorId ) => {
//            return get( 'api/moderator/districts');
//        },
    },

    page: {
        get: (id) => get('api/page/' + id),
    },

    report: {
        get: (id) => get('api/report/' + id),
        post: (report) => { // for insert and update
            // TODO adjust cache
            return post( 'api/report', report );
        },
        delete: ( id ) => del( 'api/report/'+id ),
    },

    result: {
        get: ( id ) => get( 'api/result/'+id ),
        post: ( result ) => post( 'api/result', result ), // does insert or replace based on id ( null )
        // put: ( result ) => put( 'api/result', result ),
        delete: ( id ) => del( 'api/result/'+id ),

        breed: {
            get: ( breedId, districtId, year, group ) => get( 'api/result/breed/'+breedId+'/district/'+districtId+'/year/'+year+'/group/'+group+'/results'),
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

        },
        root: {
            get: ( rootId ) => {
                return get( 'api/section/'+rootId+'/root');
            }
        }
    },

    trend: {
        color: {
            get: ( districtId, colorId ) =>
                get( 'api/results/years?district='+districtId+'&color='+colorId ),
        },
        breed: {
            get: ( districtId, breedId ) =>
                get( 'api/results/years?district='+districtId+'&breed='+breedId ),
        },
        section: {
            get: ( districtId, sectionId ) =>
                get( 'api/results/years?district='+districtId+'&section='+sectionId ),
        },
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
//        console.log( 'Auto clear cache for ', url );
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
    let cached = cache[url];
    let now = new Date().getTime(); // in ms
    if (cached && cached.time > now - settings.cache.TIMEOUT) { // fresh enough
        return cached.promise;
    } else {
        let options = {
            method: 'GET',
            headers: getHeaders()
        }
        console.log('new get', url);
        let promise = fetch(settings.api.root + url, options)
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                console.error('Fetch not ok, got null', response);
                return null;
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
            if( response.ok ) {
                return response.json();
            }
            console.error('Fetch not ok, got null', response);
            return null;
        });
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




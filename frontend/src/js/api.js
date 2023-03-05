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

    article: {
        get: (id) => get('api/article/' + id),
    },


    breed: {
        get: (id) => get( 'api/breed/'+id ),
        colors : {
            get: ( breedId ) => get( `api/breed/${breedId}/colors`)
        },
    },

    breeder: {
        get: ( breederId ) => get( 'api/breeder/'+breederId ),
        new: ( districtId ) => { // id being null
            return new Promise( ( resolve ) => {
                // TODO, remember to delete cache for parent district
                resolve( { breeder:{ id:null, name:null, email:null, districtId:districtId, clubId:null, start:new Date(), end:null, active:true, info:null }} );
            })
        },
        post: ( breeder ) => {
            return post( 'api/breeder', breeder );
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
            return get( 'api/district/'+districtId );
        },
        new: ( parentId ) => {
            return new Promise( ( resolve ) => {
                // TODO, remember to delete cache for parent district
                clear( 'api/district/'+parentId );
                resolve( { id:null, parent:parentId, name:null, fullname:null, short:null, coordinates:null, children:[], moderators:[] } );
            })
        },
        post: ( district ) => { // save, insert on id=null or update
            return post( 'api/district', district );
        },
        delete: ( districtId ) => {
            //return del( 'api/district/'+districtId ); // TODO delete or better disable !
        },

//        tree: (parentId) => {
//            return get('api/district/'+parentId+'/tree');
//        },

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

        /**
         * returns array of clubs within district hierarchy incl root id
         */
        clubs: {
            get: ( districtId ) => {
                return new Promise( resolve => {
                    get( 'api/district/'+districtId+'/descendants' ).then( response => {
                        const clubs = [];
                        let root = response.district;
                        let districts = [ root ];
                        for( let index=0; index < districts.length; index++ ) {
                            const district = districts[ index ];
                            if( district.level==='OV' ) {
                                clubs.push( district );
                            }
                            districts = districts.concat( district.children );
                        }
                        resolve( { clubs:clubs } );
                    })
                })

            }
        },

        /**
         * returns the district hierarchie incl given root id
          */
        descendants: {
            get: ( districtId ) => {
                return get( 'api/district/'+districtId+'/descendants');
            }
        },

        results: { // showing results for district, all sections etc
            //get: (districtId, sectionId, year, group) => get('api/district/' + districtId + '/section/' + sectionId + '/year/' + year + '/group/' + group + '/results/full'),
            get: (districtId, year) => {
                return get( 'api/district/'+districtId+'/results?year='+year );
            },
            section: {// for edit breeds in section, closed
                get: ( districtId, sectionId, year, group ) => {
                    return get( 'api/district/'+districtId+'/results?section='+sectionId+'&year='+year+'&group='+group );
                }
            },
            breed: { // for edit per color or breed as pigeon
                get: ( districtId, sectionId, breedId, year, group ) => {
                    return get( 'api/district/'+districtId+'/results?section='+sectionId+'&breed='+breedId+'&year='+year+'&group='+group );
                },
 //               colors: {
 //                   get: ( districtId, breedId, year, group ) => {
 //                       return get( 'api/district/'+districtId+'/results?breed='+breedId+'&year='+year+'&group='+group );
 //                   },
 //               }
            }
        },
    },

    /**
     * provides the array of ZB groups, could be extended to own table in db with info text
     */
    groups: {
        get: () => {
            return new Promise( ( resolve ) => {
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
            let moderatorPromise = Promise.resolve({moderator: {id: 0, district: districtId}});
            let districtPromise = get('api/district/' + districtId);
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
        delete: ( id ) => del( 'api/result/'+id ),

//        breed: {
//            get: ( districtId, breedId, year, group ) => get( 'api/district/'+districtId+'/results?/breed/'+breedId+'/district/'+districtId+'/year/'+year+'/group/'+group+'/results' ),
//        },
//        colors: {
////            get: ( breedId, districtId, year, group ) => get( 'api/result/colors/'+breedId+'/district/'+districtId+'/year/'+year+'/group/'+group+'/results'),
//            get: ( districtId, breedId, year, group ) => get( 'api/district/'+districtId+'/results?breed='+breedId+'&year='+year+'&group='+group ),
//        },
    },

    section: {
        get: ( id ) => get( 'api/section/'+id ),
        children: {
            get: ( parentId ) => get( 'api/section/'+parentId+'/children')
        },
//        getTree: ( parentId ) => get( 'api/section/'+parentId+'/tree'),

        breeds: { get: (sectionId) => get('api/section/' + sectionId + '/breeds'), },
//        root: {
//            get: ( rootId ) => {
//                return get( 'api/section/'+rootId+'/root');
//            }
//        }
    },

    standard: {
        get: () => get( 'api/standard' ),
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

    user: {
        login: (email, password ) => {
            return post('api/user/token', {email: email, password: password}).then( response => {
                if( response ) {
                    token = response.token;
                    const decToken = jwt_decode(token);
                    console.log( 'Token', decToken );
                    decToken.user.exp = decToken.exp;
                    user.set( decToken.user ); // user or null
                    window.sessionStorage.setItem( 'token', token );
                    cache.promises = {}; // not using previous users cache
                    return { success:true }; // success
                }
                return { success: false };
            }).catch( () => {
                return { success: false };
            });

        },
        logout: () => {
            token = null;
            user.set( null ); // user or null
            window.sessionStorage.clear();
            cache.promises = {}; // clear cache so it's not used by next user
            return true; // always success
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
        'Accept': 'application/data',
        'Content-Type': 'application/data'
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




import jwt_decode from 'jwt-decode';
import { user } from './store.js'

//uses constants from js/setting.js

export default {

    article: {
        get:    ( id ) => get('api/article/'+id ),
        post: (article ) => post( 'api/article', article ),
        put: (id, article ) => put( 'api/article/'+id, article ),
        del:    ( id ) => del( 'api/article/'+id ),

        getAll: () => get( 'api/article' ),
    },

    breed: {
        get:    ( id )         => get( 'api/breed/'+id ),
        post: (breed )     => post( 'api/breed', breed ),
        put: (id, breed )=> put( 'api/breed/'+id, breed ),
        delete: ( id )       => del( 'api/breed/'+id ),

        colors : {
            get: ( breedId ) => get( `api/breed/${breedId}/colors`)
        },
    },

    breeder: {
//        new: ( districtId ) => { // id being null
//            return new Promise( ( resolve ) => {
//                resolve( { breeder:{ id:null, name:null, email:null, districtId:districtId, clubId:null, start:new Date(), end:null, active:true, info:null }} );
//            })
//        },

        get: ( id ) => get( 'api/breeder/'+id ),
        post: (breeder ) => post( 'api/breeder', breeder ),
        put: (id, breeder ) => put( 'api/breeder/'+id, breeder ),
        //delete: ( id )       => del( 'api/breed/'+id ),

        pairs: {
            get: (breederId) => get( 'api/breeder/'+breederId+'/pair' ),
        },
        year : {
            pairs: {
                get: ( breederId, year ) => get( 'api/breeder/'+breederId+'/year/'+year+'/pair' ),
            },
        },

        results: {
            get: (breederId) => get( 'api/breeder/'+breederId+'/results' ),
        },
    },

    color: {
        get:    ( id ) => get( 'api/color/'+id ),
        post: (color ) => post( 'api/color', color ),
        put: (id, color ) => put( 'api/color/'+id, color ),
        delete: ( id ) => del( 'api/color/'+id ),
    },

    district: {
        get: ( id ) => get( 'api/district/'+id ),
        post:   ( district ) => post( 'api/district', district ),
        put: (id, district ) => put( 'api/district/'+id, district ),
        //delete: ( districtId ) => del( 'api/district/'+districtId ), // TODO delete or better disable !

        breeders: {
            get: (id) => get( 'api/district/'+id+'/breeders'),
        },

        /*
        * return direct children of district, not grandchildren etc
        */
        children: {
            get: (id) => get( 'api/district/'+id+'/children' ),
        },

        /**
         * returns the district hierarchie incl given root id
          */
        descendants: {
            get: ( districtId ) => get( 'api/district/'+districtId+'/descendants'),
        },

        report: {
            get: (id, year) => {
                console.log( 'district.report');
                return get( 'api/district/'+id+'/year/'+year+'/report' )
            },
        },

        results: { // showing results for district, all sections etc
            get: (districtId, year) => get( 'api/district/'+districtId+'/year/'+year+'/report' ),

            section: {// for edit list sections breed ( with extra like nr of results )
                get: ( districtId, sectionId, year, group ) => {
                    return get( 'api/district/'+districtId+'/results?section='+sectionId+'&year='+year+'&group='+group );
                }
            },

            breed: { // to edit breed's breed ( pigeon ) or colors ( layers ) results
                get: ( districtId, sectionId, breedId, year, group ) => {
                    return get( 'api/district/'+districtId+'/breed/'+breedId+'/results?section='+sectionId+'&year='+year+'&group='+group );
//                    return get( 'api/district/'+districtId+'/results?section='+sectionId+'&breed='+breedId+'&year='+year+'&group='+group );
                },
            }
        },
    },

    /**
     * provides the array of ZB groups, could be extended to own table in db with info text
     * in code (planned to ) replaced by local adhoc array or in settings.
     */
    groups: {
        get: () => {
            return new Promise( ( resolve ) => {
                resolve( { groups:['I', 'II', 'III' ] } );
            })
        }
    },

    log: {
        getNext: ( from=0, count=100 ) => get( 'api/log/next?from='+from+'&count='+count ),
    },

    map: {
        color: {
            get: ( year, colorId ) => get( 'api/results/districts?year='+year+'&color='+colorId ),
        },
        breed: {
            get: ( year, breedId ) => get( 'api/results/districts?year='+year+'&breed='+breedId ),
        },
        section: {
            get: ( year, sectionId ) => get( 'api/results/districts?year='+year+'&section='+sectionId ),
        },
    },

    message: { // TODO ??
        post: ( districtId, from, name, subject, message, confirm ) => {
            const data = { districtId:districtId, from:from, name:name, subject:subject, message:message, confirm:confirm };
            return post( 'api/message', data );
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
                    //clear('api/district/' + districtId);
                    return moderator;
                });
        },
        post: ( districtId, moderatorId ) => {
            let data = { user:moderatorId, district:districtId };
            //clear('api/district/' + districtId);
            return post('api/moderator', data );
        },
        delete: ( districtId, moderatorId ) => {
            let data = { user:moderatorId, district:districtId };
            //clear('api/district/' + districtId);
            return del('api/moderator', data );
        },
//        districts: ( moderatorId ) => {
//            return get( 'api/moderator/districts');
//        },
    },

    pair: {
        get: (id) => get('api/pair/' + id),
        post: (report) => { // for insert and update
            return post( 'api/pair', report );
        },
        delete: ( id ) => del( 'api/pair/'+id ),

    },

    result: {
        get: ( id ) => get( 'api/result/'+id ),
        post: ( result ) => post( 'api/result', result ), // does insert or replace based on id ( null )
        delete: ( id ) => del( 'api/result/'+id ),
        districtYear: ( districtId, year, sectionId, breedId, colorId, group ) => {
            let params = {};

            if( sectionId ) params.section = sectionId;
            if( breedId ) params.breed = breedId;
            if( colorId ) params.color = colorId;
            if( group ) params.group = group;
            let query = new URLSearchParams( params );
            return get( 'api/result/district/'+districtId+'/year/'+year+'?'+query.toString() );
        }
    },

    section: {
        get: ( id ) => get( 'api/section/'+id ),
        children: {
            get: ( parentId ) => get( 'api/section/'+parentId+'/children')
        },
//        getTree: ( parentId ) => get( 'api/section/'+parentId+'/tree'),

        breeds: {
            get: (sectionId) => get('api/section/' + sectionId + '/breeds'),
        },

        descendants: {
            get: ( sectionId ) => get( 'api/section/'+sectionId+'/descendants'),
        },
    },

    standard: {
        get: () => get( 'api/standard' ), //24 * 60 * 60 * 1000
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
            // clear all client info
            token = null;
            user.set( null ); // user or null
            window.sessionStorage.clear();
            cache.clear(); // clear cache so it's not used by next user

            return post('api/user/token', {email: email, password: password}).then( response => {
                if( response ) {
                    token = response.token;
                    const decToken = jwt_decode(token);
                    console.log( 'Token', decToken );
                    decToken.user.exp = decToken.exp;
                    user.set( decToken.user ); // user or null
                    window.sessionStorage.setItem( 'token', token );
                    cache.clear(); // not using previous users cache
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
            cache.clear(); // clear cache so it's not used by next user
            return true; // always success
        },

        reset: ( email ) => get( 'api/user/reset/'+email ),

        password: ( email, resetToken, password ) => {
            return post( 'api/user/password', { email:email, token:resetToken, password:password } ).then( response => {
                if( response ) {
                    token = response.token; // the new auth token
                    const decToken = jwt_decode(token);
                    console.log( 'Token', decToken );
                    decToken.user.exp = decToken.exp;
                    user.set( decToken.user ); // user or null
                    window.sessionStorage.setItem( 'token', token );
                    cache.clear(); // not using previous users cache
                    return { success:true }; // success
                }
                return { success:false };
            }).catch( () => { // on error
                return { success: false };
            });
        },
    },
}

// private section **************

let token = getToken();

const cache = {
    urls:{}, // url -> promise, due
    get( url ) {
        return this.urls[ url ];
    },
    put( url, promise, timeout ) {
        const now = Date.now();
        this.urls[ url ] = { promise:promise, due:now+timeout };
    },
    clear() {
        this.urls = {};
    },
    update() {
        let now = new Date().getTime(); // in ms
        const toDelete = []; // collecte old, avoiding deleting while iterating
        for( const url in this.urls ) { // check per url->promise and collect before delete
            let cached = this.urls[ url ];
            if( cached.due < now ) {
                toDelete.push( url );
            }
        }
        for( const url of toDelete ) { // delete collected from cache
            delete this.urls[ url ];
        }
    }
};

/*
 * takes token from session storage and checks for expired, then returns null.
 * also sets the user store to the user data like moderator as an array of districts and if admin.
 */
function getToken() {
    let token = window.sessionStorage.getItem( 'token' );

    if( token !== null ) { // mind, could be "null" text as well
        const decoded = jwt_decode(token);
        if( decoded.exp * 1000 > Date.now() ) {
            decoded.user.exp = decoded.exp;
            user.set(decoded.user); // user from token or null
        } else {
            user.set( null ); // expired
            token =  null;
        }
    } else {
        user.set( null );
    }
    return token;
}

function getHeaders() {
    let headers = {
        'Accept': 'application/json', // expected response
        'Content-Type': 'application/json' // send body
    }
    if( token ) {
        headers[ 'Authorization'] = 'Bearer '+token;
    }
    return headers;
}


/**** FETYCH OPERATIONS ****/

async function get( url, timeout = CACHETIMEOUT ) {
//    let cached = cache.get( url );
//    let now = Date.now(); //new Date().getTime(); // in ms
//    if( cached && cached.due > now ) { // cached and still fresh
//        return cached.promise;
//    } else {
        let options = {
            method: 'GET',
            headers: getHeaders()
        }
        let promise = fetch(APIROOT+url, options).then( response => {
            if (response.ok) {
                return response.json();
            }
            console.error('Fetch not ok', response);
            return null;
        });
        cache.put( url, promise, timeout );
        return promise;
//    }
}

async function post( url, data ) {
    cache.clear(); // empty cache on every post, bit raw, but it works fine

    let options = {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify( data ),
    }
    return fetch( APIROOT + url, options ).then( response => {
        if( response.ok ) {
            return response.json();
        }
        console.error('Post Fetch not ok', response);
        return null;
    });
}

async function put( url, data ) {
    cache.clear(); // empty cache on every post, bit raw, but it works fine

    let options = {
        method: 'PUT',
        headers: getHeaders(),
        body: JSON.stringify( data ),
    }
    return fetch( APIROOT + url, options )
        .then( response => {
            if( response.ok ) {
                return response.json();
            }
            console.error('Put Fetch not ok', response);
            return null;
        });
}

async function del( url ) {
    cache.clear();
    let options = {
        method: 'DELETE',
        headers: getHeaders()
    }
    return fetch( APIROOT + url, options)
        .then( response => {
            if( response.ok ) {
                return response.json();
            }
            throw response;
        });
}


/**
 * timers:
 * 1. clears item from cache if due
 */
setInterval(  cache.update, CACHECHECKINTERVAL ); // once a minute






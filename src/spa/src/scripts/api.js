
const delay = 250;


let cache = {
    promises: {} // url -> promise, time
};

let token = null;






export default {
    user: {
        get: (id) => {
            return get( '/api/user/'+id );
        },
        token: ( email, password ) => {
            return post('/api/token', {email: email, password: password});
        },
    },
    breeder: {
        get: (id) => {
            return get( 'api/breeder/'+id );
        },
        getPairs: (id) => {
            return null; // get( 'api/breeder/'+id+'/pairs' );
        },
        getResults: (id) => {
            return get( 'api/breeder/'+id+'/results' );
        },
        getYears: (id) => {
            return get( 'api/breeder/'+id+'/years' );
        },
    },
    getSections: ( rootId ) => {
        console.log( 'api getSections' );
        return get( '/api/sections/'+rootId );
    },
    getSection: ( sectionId ) => {
        console.log( 'api getSection', sectionId );
        return get( 'api/section/'+sectionId );
    },
    getBreed: ( breedId ) => {
        console.log( 'api getBreed', breedId );
        return get( 'api/breed/'+breedId );
    },
    getColor: ( colorId ) => {
        console.log( 'api getColor', colorId );
        return get( 'api/color/'+colorId );
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

        breeders: (districtId) => {
            return get( 'api/district/'+districtId+'/breeders');
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

    pair: {
        'new': ( breederId ) => {
            console.log( 'api new Pair for ', breederId );
            let breederPromise = get( 'api/breeder/'+breederId );
            let pairPromise = Promise.resolve( {
                breeder: { id:breederId, name:null },
                district:null ,
                breed: null, color:null,
                year:2022, name: null, paired:null, group:1,
                parents: [
                    { sex:'1.0', country:'D', ring:null, score:null, parent_pair:null },
                    { sex:'0.1', country:'D', ring:null, score:null, parent_pair:null },
                    { sex:'0.1', country:'D', ring:null, score:null, parent_pair:null },
                    { sex:'0.1', country:'D', ring:null, score:null, parent_pair:null },
                ],
                lay: { start:null, until:null, eggs:null },
                broods: [ {start:null, eggs:null, fertile:null, hatched:null }, {start:null, eggs:null, fertile:null, hatched:null }, {start:null, eggs:null, fertile:null, hatched:null } ],
                scores: { p89:null, p90:null, p91:null, p92:null, p93:null, p94:null, p95:null, p96:null, p97:null},
            });
            return Promise.all( [ pairPromise, breederPromise ] )
                .then( responses => {
                    let pair = responses[0];
                    pair.breeder = responses[1];
                    // clear caches
                    return pair;
                });
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
        //console.log('Get', url);
        let promise = fetch(url, options)
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
    return fetch( url, options )
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
    return fetch( url, options )
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
    return fetch(url, options)
        .then( response => {
            if( response.ok ) {
                return response.json();
            }
            throw response;
        });
}


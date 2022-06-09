
const delay = 250;


let cache = {
    promises: {} // url -> promise, time
};

let token = null;






export default {
    getToken: ( email, password ) => {
        console.log('api getToken', email);
        return post('/api/token', {email: email, password: password});
    },
    user: {
        get: (id) => {
            return get( '/api/user/'+id );
        },
    },
    getUser: ( id ) => {
        console.log( 'api getUser', id );
        return get( '/api/user/'+id );
    },
    getUserResults: ( id ) => {
        console.log( 'api getUserResults', id );
        return get( '/api/breeder/'+id+'/results' );
    },
    getModeratorDistricts: ( id ) => {
        console.log( 'api getModeratorDistricts', id );
        return get( '/api/moderator/'+id+'/districts' );
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
                resolve( { district: { id:0, parent:parentId, name:null, fullname:null, short:null, coordinates:null, children:[], moderators:[] } } );
            })
        },
        tree: (parentId) => {
            return get('api/district/'+parentId+'/tree');
        }
    },


    postDistrict: ( district ) => { // insert
        console.log( 'api postDistrict' );
        return post( 'api/district', district );
    },
    putDistrict: ( district ) => { // updating
        console.log( 'api postDistrict' );
        return put( 'api/district/'+district.id, district );
    },
    deleteDistrict: ( districtId ) => {
        console.log( 'api deleteDistrict' );
        //return del( 'api/district/'+districtId ); // TODO delete or better disable !
    },

    moderator: {
        new: (districtId) => {
            console.log('api new moderator');
            let moderatorPromise = Promise.resolve({moderator: {id: 0, district: districtId}});
            let districtPromise = get('api/district/' + districtId);            ;
            let candidatesPromise = get('/api/users');

            return Promise.all([moderatorPromise, districtPromise, candidatesPromise])
                .then(responses => {
                    let moderator = responses[0].moderator;
                    moderator.district = responses[1].district;
                    moderator.users = responses[2].users;
                    clear('api/district/' + districtId);
                    return {moderator: moderator};
                })

            /*            return new Promise((resolve) => {
                            // TODO, remember to delete cache for parent district
                            clear('api/district/' + districtId);
                            resolve({moderator: {id: 0, moderator: 0}});
                        })
            */
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

    },







    getBreedBreeders: ( breedId ) => {
        console.log( 'api getBreedBreeders', breedId );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve( [ { id:1, name:'Eelco'}, { id:2, name:'Maike'} ] );
            }, delay );
        })
    },
    getBreedResults: ( breedId ) => {
        console.log( 'api getBreedResultss', breedId );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve([ { id:1, pairs:5, breeders:3, year:2020, henns:18, layed:160, eggs:100, fertilized:0.5, hatched:0.4, displayed:24, showed:95 } ] );
            }, delay );
        })
    },

    getBreedDistrictsResults: ( breedId, year ) => {
        console.log( 'api getBreedDistrictsResults', breedId, year );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve([ { id:1, pairs:5, breeders:3, year:2020, henns:18, layed:160, eggs:100, fertilized:0.5, hatched:0.4, displayed:24, showed:95 }, { id:2, pairs:5, breeders:3, year:2021, henns:18, layed:160, eggs:100, fertilized:0.5, hatched:0.4, displayed:24, showed:95 } ] );
            }, delay );
        })
    },

    getBreederBreeds: ( breederId ) => {
        console.log( 'api getBreederBreeds', breederId );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve()
            }, delay );
        })
    },

    getBreederPairs:( breederId ) => {
        console.log( 'api getBreederPairs', breederId );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve( [ {id:1, name:'a'},{id:2, name:'b'},{id:3, name:'c'} ]);
            }, delay );
        })
    },

    getBreederResults:( breederId ) => {
        console.log( 'api getBreederResults', breederId );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve([ { id:1, pairs:5, breeders:3, year:2020, henns:18, layed:160, eggs:100, fertilized:0.5, hatched:0.4, displayed:24, showed:95 } ] );
            }, delay );
        })
    },

    getDistrictBreeders:( districtId ) => {
        console.log( 'api getDistrictBreeders', districtId );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve()
            }, delay );
        })
    },

    getDistrictResults: ( districtId, year ) => {
        console.log( 'api getDistrictYearResults', districtId, year );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve()
            }, delay );
        })
    },

    getPair: ( pairId ) => {
        console.log( 'api getPair', pairId );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve( { id:1, code:'C3', year:2020, breeder:{ id:1, name:'Eelco', number:1054} } );
            }, 500 );
        })
    },

    getSectionBreeds: ( sectionId ) => {
        console.log( 'api getSectionBreeds', sectionId );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve( [ {id:1, name:'Bielie'}, {id:2, name:'Wellie'} ] );
            }, delay );
        })
    },

    getSectionResults: ( sectionId, year ) => {
        console.log( 'api getSectionResults', sectionId, year );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve( [ { id:1, pairs:5, breeders:3, year:2020, henns:18, layed:160, eggs:100, fertilized:0.5, hatched:0.4, displayed:24, showed:95 }, { id:2, pairs:5, breeders:3, year:2021, henns:18, layed:160, eggs:100, fertilized:0.5, hatched:0.4, displayed:24, showed:95 } ] );
            }, delay );
        })
    },

    getSectionDistrictsResults: ( sectionId, year ) => {
        console.log( 'api getSectionResults', sectionId, year );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve( [ { id:1, pairs:5, breeders:3, year:2020, henns:18, layed:160, eggs:100, fertilized:0.5, hatched:0.4, displayed:24, showed:95 }, { id:2, pairs:5, breeders:3, year:2021, henns:18, layed:160, eggs:100, fertilized:0.5, hatched:0.4, displayed:24, showed:95 } ] );
            }, delay );
        })
    },

    getSectionTrend: ( districtId, sectionId ) => {
        console.log( 'api getDistrictsSectionResultsResults', districtId, sectionId );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve( [ { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94}, { id:1, year:2021, laying:160, fertilization: 0.9, hatching:0.8, showing:94} ] );
            }, delay );
        })

    },

    getBreedTrend: ( districtId, breedId ) => {
        console.log( 'api getBreedTrend', districtId, breedId );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve( [ { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94}, { id:1, year:2021, laying:160, fertilization: 0.9, hatching:0.8, showing:94} ] );
            }, delay );
        })

    },

    getColorTrend: ( districtId, colorId ) => {
        console.log( 'api getColorTrend', districtId, colorId );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve( [ { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94}, { id:1, year:2021, laying:160, fertilization: 0.9, hatching:0.8, showing:94} ] );
            }, delay );
        })

    },

    getSectionMap: (year, sectionId ) => {
        console.log( 'api getSectionMap', year, sectionId );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve( [ { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94}, { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94} ] );
            }, delay );
        })

    },

    getBreedMap: (year, breedId ) => {
        console.log( 'api getBreedMap', year, breedId );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve( [ { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94}, { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94} ] );
            }, delay );
        })

    },

    getColorMap: (year, colorId ) => {
        console.log( 'api getColorMap', year, colorId );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve( [ { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94}, { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94} ] );
            }, delay );
        })

    }
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


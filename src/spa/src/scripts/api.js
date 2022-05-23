
const delay = 250;


let cache = {
    promises: {} // url -> promise, time
};

let token = null;

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
        console.log( 'Cache delete', url );
        delete cache[ url ];
    }
}, settings.cache.TIMEOUT )

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
        console.log('Get', url);
        let promise = fetch(url, options).then( response => {
            console.log('Response ', response);
            if( response.ok ) {
                console.log( 'ger ok');
                return response.json();
            } else {
                console.log( 'ger error');
                throw new Error( 'Status '+response.status );
            }
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
    console.log( 'POST', url, data );
    return fetch( url, options )
        .then( response => response.json() )
        .catch( e => { throw new Error('Oeps') } );
}






export default {
    getToken: ( email, password ) => {
        console.log('api getToken', email);
        const promise = post('/api/token', {email: email, password: password});
        promise.then(data => {
            console.log('Store Token', data);
            token = data.token;
        });
        return promise;
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
    getSections: ( root ) => {
        console.log( 'api getSections' );
        return new Promise( ( resolve ) => {
            setTimeout( () => {
                console.log( 'Timedout' );
                resolve( { id:1, name:'Geflügel', children:[ {id:2,name:'Groß- und Wassergeflügel'}, {id:3,name:'Hühner', children:[{ id:4,name:'Hühner'},{ id:5,name:'Zwerghühner'},{ id:6,name:'Legewachteln'}]} ] });
            }, delay );
        })
    }
}

/// temp ****************************************

export function getBreed( breedId ) {
    console.log( 'api getBreed', breedId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve( { id:1024, name:'Bielie', colors:[ { id:1, name:'A' }, { id:2, name:'B' } ] } );
        }, delay );
    })
}

export function getBreedBreeders( breedId ) {
    console.log( 'api getBreedBreeders', breedId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve( [ { id:1, name:'Eelco'}, { id:2, name:'Maike'} ] );
        }, delay );
    })
}
export function getBreedResults( breedId ) {
    console.log( 'api getBreedResultss', breedId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve([ { id:1, pairs:5, breeders:3, year:2020, henns:18, layed:160, eggs:100, fertilized:0.5, hatched:0.4, displayed:24, showed:95 } ] );
        }, delay );
    })
}

export function getBreedDistrictsResults( breedId, year ) {
    console.log( 'api getBreedDistrictsResults', breedId, year );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve([ { id:1, pairs:5, breeders:3, year:2020, henns:18, layed:160, eggs:100, fertilized:0.5, hatched:0.4, displayed:24, showed:95 }, { id:2, pairs:5, breeders:3, year:2021, henns:18, layed:160, eggs:100, fertilized:0.5, hatched:0.4, displayed:24, showed:95 } ] );
        }, delay );
    })
}



export function getBreederBreeds( breederId ) {
    console.log( 'api getBreederBreeds', breederId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve()
        }, delay );
    })
}

export function getBreederPairs( breederId ) {
    console.log( 'api getBreederPairs', breederId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve( [ {id:1, name:'a'},{id:2, name:'b'},{id:3, name:'c'} ]);
        }, delay );
    })
}

export function getBreederResults( breederId ) {
    console.log( 'api getBreederResults', breederId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve([ { id:1, pairs:5, breeders:3, year:2020, henns:18, layed:160, eggs:100, fertilized:0.5, hatched:0.4, displayed:24, showed:95 } ] );
        }, delay );
    })
}

export function getColor( colorId ) {
    console.log( 'api getColor', colorId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve( { id:10, name:'Mooiste kleur'})
        }, delay );
    })
}

export function getDistricts() {
    console.log( 'api getDistrict');
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve( [ {id:1, name:'Grafschaft'}, {id:2, name:'Oldenburg'} ] );
        }, delay );
    })
}

export function getDistrict( districtId ) {
    console.log( 'api getDistrict', districtId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve( {id:1,name:'a' } );
        }, delay );
    })
}

export function getDistrictBreeders( districtId ) {
    console.log( 'api getDistrictBreeders', districtId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve()
        }, delay );
    })
}

export function getDistrictResults( districtId, year ) {
    console.log( 'api getDistrictYearResults', districtId, year );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve()
        }, delay );
    })
}

export function getPair( pairId ) {
    console.log( 'api getPair', pairId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve( { id:1, code:'C3', year:2020, breeder:{ id:1, name:'Eelco', number:1054} } );
        }, 500 );
    })
}



export function getSection( sectionId ) {
    console.log( 'api getSection', sectionId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve( {id:1,name:'a', breeds:[{id:1, name:'Bielie'}, {id:2, name:'Wellie'} ] } );
        }, delay );
    })
}

export function getSectionBreeds( sectionId ) {
    console.log( 'api getSectionBreeds', sectionId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve( [ {id:1, name:'Bielie'}, {id:2, name:'Wellie'} ] );
        }, delay );
    })
}

export function getSectionResults( sectionId, year ) {
    console.log( 'api getSectionResults', sectionId, year );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve( [ { id:1, pairs:5, breeders:3, year:2020, henns:18, layed:160, eggs:100, fertilized:0.5, hatched:0.4, displayed:24, showed:95 }, { id:2, pairs:5, breeders:3, year:2021, henns:18, layed:160, eggs:100, fertilized:0.5, hatched:0.4, displayed:24, showed:95 } ] );
        }, delay );
    })
}

export function getSectionDistrictsResults( sectionId, year ) {
    console.log( 'api getSectionResults', sectionId, year );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve( [ { id:1, pairs:5, breeders:3, year:2020, henns:18, layed:160, eggs:100, fertilized:0.5, hatched:0.4, displayed:24, showed:95 }, { id:2, pairs:5, breeders:3, year:2021, henns:18, layed:160, eggs:100, fertilized:0.5, hatched:0.4, displayed:24, showed:95 } ] );
        }, delay );
    })
}

export function getSectionTrend( districtId, sectionId ) {
    console.log( 'api getDistrictsSectionResultsResults', districtId, sectionId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve( [ { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94}, { id:1, year:2021, laying:160, fertilization: 0.9, hatching:0.8, showing:94} ] );
        }, delay );
    })

}
export function getBreedTrend( districtId, breedId ) {
    console.log( 'api getBreedTrend', districtId, breedId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve( [ { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94}, { id:1, year:2021, laying:160, fertilization: 0.9, hatching:0.8, showing:94} ] );
        }, delay );
    })

}
export function getColorTrend( districtId, colorId ) {
    console.log( 'api getColorTrend', districtId, colorId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve( [ { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94}, { id:1, year:2021, laying:160, fertilization: 0.9, hatching:0.8, showing:94} ] );
        }, delay );
    })

}


export function getSectionMap(year, sectionId ) {
    console.log( 'api getSectionMap', year, sectionId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve( [ { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94}, { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94} ] );
        }, delay );
    })

}

export function getBreedMap(year, breedId ) {
    console.log( 'api getBreedMap', year, breedId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve( [ { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94}, { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94} ] );
        }, delay );
    })

}

export function getColorMap(year, colorId ) {
    console.log( 'api getColorMap', year, colorId );
    return new Promise( ( resolve ) => {
        setTimeout( () => {
            console.log( 'Timedout' );
            resolve( [ { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94}, { id:1, year:2020, laying:160, fertilization: 0.9, hatching:0.8, showing:94} ] );
        }, delay );
    })

}

/*
export default {

    getToken, getUser,
    getBreed, getBreedBreeders, getBreedResults, getBreedDistrictsResults,
    getBreederBreeds, getBreederPairs, getBreederResults,
    getColor,
    getDistricts, getDistrict, getDistrictBreeders, getDistrictResults,
    getPair,
    getSections, getSection, getSectionBreeds, getSectionResults, getSectionDistrictsResults,
    getSectionMap, getBreedMap, getColorMap,
    getSectionTrend, getBreedTrend, getColorTrend
}


 */

function template( object ) {
    return JSON.parse(JSON.stringify( object )); // create clone off object
}

export function newBreed() {
    return template( { sectionId:4, breedId:1024, colorId:8543 } );
}

export function newBreeder( districtId ) {
    console.log( 'newBreeder ', districtId );
    return template( { id:0, name:'a', districtId:districtId, clubId:null, email:'', password:'' } );
}

// in Report/Parents
//export function newParent( sex= '0.1' ) {
//    sex = sex === '1.0' || sex === '0.1' ? sex : '0.1'; // make sure it's valid or '0.1'
//    return template( { id:0, reportId:0, sex:sex, ring:null, score:null } );
//}

export function newLay() {
    return template({id: 0, reportId: 0, start: '2022-02-01', end: '2022-05-01', eggs: 50, weight: 46});
}

export function newBrood( sectionId ) {
    if( sectionId === 5 ) {
        return template({ id:0, reportId:0, start:null, eggs:2, hatched:null, ringed:null, rings:[ null, null ] } );
    } else {
        return template( { id:0, reportId:0, start:'2022-03-01', eggs:100, fertile:90, hatched:80, ringed:null, offspring:[] } );
    }
}

export function newShow() {
    return template(  { 89:null, 90:null, 91:2, 92:null, 93:null, 94:null, 95:1, 96:null, 97:null } );
}

export function newReport( districtId, breederId )  {
    console.log('Template', breederId);
    return template({
        id: 0, t:9,
        breederId:breederId, districtId:districtId, year: new Date().getFullYear(), group: 'I',
        sectionId: 4, breedId: 1024, colorId: 8543,
        name: 'A', paired: '2021-12-31', notes: 'Test',
        parents: [
            newParent( '1.0' ), newParent( '0.1' )
        ],
        lay: newLay(),
        broods: [],
        show: newShow(),
    } );
}

export function newResult() {
    return template({
        id:0,
        reportId:null, districtId:null,
        year:2022, group:'I', breeders:null, pairs:null,
        sectionId:4, breedId:1024, colorId:8543,         // for debugging
        layDames: null, leyEggs:null, layWeight:null,
        broodEggs:null, broodFertile:null, broodHatched:null,
        showCount:null, showScore:null
    } );
}


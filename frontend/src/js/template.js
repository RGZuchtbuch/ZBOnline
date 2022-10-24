export function clone( object ) {
    return JSON.parse(JSON.stringify( object ));
}

export const breedTpl = {
    sectionId:4, breedId:1024, colorId:8543
}

export const parentTpl = {
    id:0, reportId:0,
    sex:null, ring:null, score:null
}
export const parentSireTpl = {
    id:0, reportId:0,
    sex:'1.0', ring:'D21 A 999', score:94.3
}
export const parentDameTpl = {
    id:0, reportId:0,
    sex:'0.1', ring:'20Z700', score:95.3
}

export const layTpl = {
    id:0, reportId:0,
    start:'2022-02-01', end:'2022-05-01', eggs:50, weight:46,
}

export const broodTpl = {
    id:0, reportId:0,
    start:'2022-03-01', eggs:100, fertile:90, hatched:80, ringed:null, offspring:[]
}

export const broodPigeonTpl = {
    id:0, reportId:0,
    start:null, hatched:null, ringed:null, rings:[ null, null ]
}

export const showTpl = {
    89:null, 90:null, 91:2, 92:null, 93:null, 94:null, 95:1, 96:null, 97:null
}


export const reportTpl = {
    id:0,
    breederId:1, districtId:6, year:2022, group:'I',
    sectionId:4, breedId:1024, colorId:8543,
    name:'A', paired:'2021-12-31', notes:'Test',
    parents: [ parentSireTpl, parentDameTpl ],
    lay: layTpl,
    broods: [ broodTpl ],
    show: showTpl
}

export const resultTpl = {
    id:0,
    reportId:null, districtId:null,
    year:2022, group:'I', breeders:null, pairs:null,
    sectionId:4, breedId:1024, colorId:8543,         // for debugging
    layDames: null, leyEggs:null, layWeight:null,
    broodEggs:null, broodFertile:null, broodHatched:null,
    showCount:null, showScore:null
}

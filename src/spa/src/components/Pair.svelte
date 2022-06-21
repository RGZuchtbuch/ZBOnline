<script>
    import { onMount } from 'svelte';
    import api from '../scripts/api.js';

    export let promise;
    let pair = null;// = { section:null, breed:null, color:null };

    // to improve updates, set on promise response in onMount
    let parents = null;
    let lay = null;
    let broods = null;
    let show = null;

    let result = null;


    // for selecting breed & color
    let section;
    let sections = [];
    let breed;
    let breeds = [];
    let color;
    let colors = [];
    let groups = [ { id:1, name:'I'}, { id:1, name:'II'}, { id:1, name:'III'} ];
    let sexes = [ '1.0', '0.1' ];
    let countries = [ 'D' ];

    let valid = false;



    loadSections();
    $: onChangeSection( section );
    $: onChangeBreed( breed );
    $: onChangeColor( color );

    $: calculateLay( parents, lay );
    $: calculateBrood( broods );
    $: calculateShow( show );

    $: validate( pair, breed, color, parents );

    onMount( () => {
        promise.then( data => {
            pair = data;
            parents = pair.parents;
            lay = pair.lay;
            broods = pair.broods;
            show = pair.show;
            result = pair.result;
        });
    });

    function validate( ...a ) {
        if( pair ) {
            //console.log( a );
            valid = true;
            valid &= pair.name!== null && pair.year > 1800 && pair.year < 2999;
            if (section && section.id === 5) {
                console.log('Pigeons');
                valid &= breed !== null;
                console.log('V1', valid);
                valid &= parents.length === 2 && parents[0].year>0 && parents[0].ring>'' && parents[1].year>0 && parents[1].ring>'';
                console.log( 'cHECK', )
            } else {
                console.log('Chickens', color);
                valid &= color !== null;
                valid &= parents.length >= 2 && parents[0].year>0 && parents[0].ring>'' && parents[1].year>0 && parents[1].ring>'';
            }

            console.log('Valid', valid);
        }
    }

    function calculateLay( parents, lay ) {
        if( pair && lay && result ) {
            console.log( 'Calculate lay', lay )

            if(  lay.eggs && lay.eggs >= 0 && lay.eggs <= 9999 ) {
                // dames
                let dames = 0;
                for (let parent of pair.parents) {
                    if (parent.sex === '0.1' && parent.ring) dames++;
                }
                // eggs
                if (dames > 0) {
                    const start = new Date(pair.lay.start);
                    const until = new Date(pair.lay.until); // inclusive
                    const days = 1 + Math.floor((until - start) / (1000 * 60 * 60 * 24));
                    const eggs = Math.floor(pair.lay.eggs * 274 / (dames * days));
                    if( eggs >= 0 && eggs <=366 ) {
                        result.lay = {dames: dames, eggs: eggs};
                        return;
                    }
                }
            }
            result.lay = { dames:null, eggs:null };
        }
    }

    function calculateBrood( broods ) {
        if( pair && broods && result ) {
            console.log( 'Calculate broods', broods )
            let eggs = 0;
            let fertile = 0;
            let hatched = 0;
            for (let brood of broods) {
                if (brood.eggs > 0 &&
                    brood.fertile >= 0 && brood.fertile <= brood.eggs &&
                    brood.hatched >= 0 && brood.hatched <= brood.fertile) {
                    brood.valid = true;
                    eggs += brood.eggs;
                    fertile += brood.fertile || 0;
                    hatched += brood.hatched || 0;
                } else {
                    brood.valid = false;
                }
            }
            if (eggs > 0 && fertile !== null && hatched !== null) {
                result.brood = { eggs:eggs, fertile:fertile/eggs, hatched:hatched/eggs };
                return;
            }
            result.brood = { eggs:null, fertile:null, hatched:null };
        }
    }

    function calculateShow( show ) {
        if( show && result ) {
            console.log( 'Calculate show', show )
            let count = 0;
            let total = 0;
            for (let score in show.scores ) {
                count += show.scores[score];
                total += score * show.scores[score];
            }
            if (count > 0) {
                result.show = { count:count, score:total/count };
                return;
            }
            result.show = { count:null, score:null };
        }
    }

    function loadSections() {
        console.log( 'Load Sections' );
        api.section.getChildren( 2 ).then( data => {
            sections = data;
        });
    }
    function onChangeSection( section ) {
        if( section ) {
            pair.section = section;
            api.section.getBreeds(section.id).then(data => {
                breeds = data;
                breed = null;
                colors = [];
                color = null;
            });
        } else {
            breeds = [];
            breed = null;
            colors = [];
            color = null;
        }
    }
    function onChangeBreed( breed ) {
        if( breed && breed.id ) {
            pair.breed = breed;
            api.breed.getColors( breed.id ).then(data => {
                colors = data;
                if( colors.length === 1) {
                    color = colors[0];
                } else {
                    color = null;
                }
            });
        } else {
            colors = [];
            color = null;
        }
    }

    function onChangeColor( color ) {
        if( color ) {
            pair.color = color;
        }
    }

    function onAddParent() {
        parents.push( {sex: '0.1', country: 'D', year:null, ring: null, score: null, parents: { id:null, breeder:null, year:2021, name:null }} );
        parents = parents; // trigger, also to keep pair happy
    }

    function onAddBrood() {
        broods.push( {start: null, eggs: null, fertile: null, hatched: null} );
        broods = broods;
    }

    function fixed( number, decimals=0, factor=1  ) {
        if( number === null ) {
            return '';
        } else {
            return (factor * number).toFixed( decimals );
        }
    }
</script>

<div class='flex flex-col bg-gray-100  whitespace-nowrap'>
    <h3 class=''>Meldung {valid?'ok':'error'}</h3>
    {#if pair }
        <div class='flex flex-row gap-2'>
            <div class='flex flex-col w-64 border border-b-red-600'>
                <div class='text-xs text-gray-600'>Züchter</div>
                <div class=''>{pair.breeder.name} [{pair.breeder.id}]</div>
            </div>
            <div class='flex flex-col border border-b-red-600'>
                <div class='text-xs text-gray-600'>Jahr</div>
                <input class='w-16' type='number' min=1900 max=2099 step=1 bind:value={pair.year} >
            </div>
            <div class='flex flex-col border border-b-red-600'>
                <div class='text-xs text-gray-600'>Nummer</div>
                <input class='w-16' type='text' maxlength=8 bind:value={pair.name} >
            </div>
            <div class='flex flex-col border border-b-red-600'>
                <div class='text-xs text-gray-600'>Gruppe</div>
                <select class='w-12' bind:value={pair.group}>
                    {#each groups as group} <option value={group.id}>{group.name}</option>{/each}
                </select>
            </div>
        </div>

        {#if pair.year > 0 && pair.name}
            <div class='flex flex-row gap-2'>
                <div class='flex flex-col border border-b-red-600'>
                    <div class='text-xs text-gray-600'>Sparte {section?'['+section.id+']':'x'}</div>
                    <select class='w-32' bind:value={section}>
                        <option value={null}>?</option>
                        {#each sections as section}
                            <option value={section}>{section.name}</option>
                        {/each}
                    </select>
                </div>

                <div class='flex flex-col border border-b-red-600'>
                    <div class='text-xs text-gray-600'>Rasse {breed?'['+breed.id+']':'x'}</div>
                    <select class='w-64' bind:value={breed} disabled={! section}>
                        <option value={null} selected={! breed}>? ({breeds.length})</option>
                        {#if breeds }
                            {#each breeds as option}
                                <option value={option}>{option.name}</option>
                            {/each}
                        {/if}
                    </select>
                </div>

                <div class='flex flex-col border border-b-red-600'>
                    <div class='text-xs text-gray-600'>Farbe {color?'['+color.id+']':'x'}</div>
                    <select class='w-64' bind:value={color} disabled={(section && section.id===5) || ! breed }>
                        <option value={null} selected={!color}>? ({colors.length})</option>
                        {#if colors}
                            {#each colors as color}
                                <option value={color}>{color.name}</option>
                            {/each}
                        {/if}
                    </select>
                </div>
            </div>
        {/if}

        <div>
            {#if section && breed && (section.id===5 || color)}
                <div class='flex flex-col'>
                    <h4>Elterntiere <span class='clickable' on:click={onAddParent}>[+]</span></h4>
                    <div class='flex flex-row gap-x-2 text-xs text-gray-600'>
                        <div class='w-12 '><br>Geschlecht</div>
                        <div class='w-10'><br>Land</div>
                        <div class='w-10'><br>Jahr</div>
                        <div class='w-16'><br>Ring</div>
                        <div class='w-10'><br>Ø Note</div>
                        <div class='flex flex-col text-xs text-gray-600 whitespace-nowrap'>
                            <div>Abstammung</div>
                            <div class='flex flex-row gap-x-1'>
                                <div class='w-16'>Züchter</div>
                                <div class='w-8'>Jahr</div>
                                <div class='w-16'>Stamm</div>
                            </div>
                        </div>
                    </div>
                    {#each parents as parent}
                        <div class='flex flex-row gap-x-2 mb-1'>

                            <select class='w-12 border border-b-red-600' bind:value={parent.sex}>
                                {#each sexes as sex} <option value={sex}>{sex}</option>{/each}
                            </select>

                            <select class='w-10 border border-b-red-600' bind:value={parent.country}>
                                {#each countries as country} <option value={country}>{country}</option>{/each}
                            </select>

                            <input class='w-10 border border-b-red-600 text-right' type='number' min=00 max=99 step=1 maxlength='2' bind:value={parent.year}>

                            <input class='w-16 border border-b-red-600' type='text' maxlength='8' bind:value={parent.ring} placeholder='AZ 999'>

                            <input class='w-10 border border-b-red-600' type='number' min=90 max=97 maxlength=2 bind:value={parent.score}>

                            <div class='flex flex-row border border-b-red-600 gap-x-1' >
                                <input class='w-16' type='number' min=0 max=99999 step=1 bind:value={parent.parents.breeder}>
                                <input class='w-8' bind:value={parent.year} >
                                <input class='w-16' type='text' maxlength=8 bind:value={parent.parents.name} >
                            </div>
                        </div>
                    {/each}
                </div>
            {/if}

            {#if section && section.id!==5 && breed && color}
                <div>
                    <h4>Gesammelte Eier</h4>
                    <div class='flex flex-row gap-2'>
                        <div class='border border-b-red-600'>
                            <div class='text-xs text-gray-600'>Start Datum</div>
                            <input class='w-32' type='date' bind:value={lay.start}>
                        </div>
                        <div class='border border-b-red-600'>
                            <div class='text-xs text-gray-600'>Bis (inkl) Datum</div>
                            <input class='w-32' type='date' bind:value={lay.until}>
                        </div>
                        <div class='border border-b-red-600'>
                            <div class='text-xs text-gray-600'># Eier</div>
                            <input class='w-16' type='number' min=0 max=365 step=1 bind:value={lay.eggs}>
                        </div>
                    </div>
                </div>
            {/if}


            {#if section && section.id !== 5 && breed && color}
                <div class='flex flex-col'>
                    <h4>Bruten <span class='clickable' on:click={onAddBrood}>[+]</span></h4>
                    <div class='flex flex-row gap-x-2 text-xs text-gray-600'>
                        <div class='w-32'>Start Datum</div>
                        <div class='w-16'># Eier</div>
                        <div class='w-16'># Befruchtet</div>
                        <div class='w-16'># Geschlüpft</div>
                    </div>
                    <div class='flex flex-col gap-1' >
                        {#each broods as brood}
                            <div class='flex flex-row gap-x-2 mb-1'>
                                <input class='w-32' type='date' bind:value={brood.start}>
                                <input class='w-16' type='number' min=0 max=9999 step=1 bind:value={brood.eggs}>
                                <input class='w-16' type='number' min=0 max={brood.eggs} step=1 bind:value={brood.fertile}>
                                <input class='w-16' type='number' min=0 max={brood.fertile} step=1 bind:value={brood.hatched}>
                            </div>
                        {/each}
                    </div>
                </div>
            {/if}

            {#if section && section.id === 5 && breed}
                <div class='flex flex-col'>
                    <h4>Tauben Bruten <span class='clickable' on:click={onAddBrood}>[+]</span></h4>
                    <div class='flex flex-row gap-x-2 text-xs text-gray-600'>
                        <div class='w-32'>Start Datum</div>
                        <div class='w-16'># Eier</div>
                        <div class='w-16'># Befruchtet</div>
                        <div class='w-16'># Geschlüpft</div>
                    </div>
                    <div class='flex flex-col gap-1' >
                        {#each broods as brood}
                            <div class='flex flex-row gap-x-2 mb-1'>
                                <input class='w-32' type='date' bind:value={brood.start}>
                                <input class='w-16' type='number' min=0 max=9999 step=1 bind:value={brood.eggs}>
                                <input class='w-16' type='number' min=0 max={brood.eggs} step=1 bind:value={brood.fertile}>
                                <input class='w-16' type='number' min=0 max={brood.fertile} step=1 bind:value={brood.hatched}>
                            </div>
                        {/each}
                    </div>
                </div>
            {/if}

            {#if section && breed && (section.id===5 || color)}
                <div>
                    <h4>Ausstellungs bewertungen</h4>
                    <div class='flex flex-row gap-2'>
                        {#each Object.keys(show.scores) as key}
                            <div class='flex-flex-col'>
                                <div class='text-xs text-gray-600'>{#if key=='89'}U/O{:else} {key} {/if}</div>
                                <input class='w-12' type='number' min=0 max=9999 step=1 bind:value={ show.scores[key] } >
                            </div>
                        {/each}
                    </div>
                </div>
            {/if}

            {#if section && breed && (section.id===5 || color)}
                <div>
                    <div class='flex-flex-col'>
                        <div class='text-xs text-gray-600'>Notizen</div>
                        <textarea class='w-full h-16' bind:value={pair.remarks}></textarea>
                    </div>
                </div>
            {/if}


            {#if valid}
                <div class='flex flex-col align-right'>
                    <span>Speichern</span>
                </div>
            {/if}
        </div>

        <hr>
        <div>
            <h4>Leistungenswerte (berechnet):</h4>
            <div class='flex flex-row gap-2'>
                <div class='flex flex-col gap-y-1'>
                    <div class='text-xs text-gray-600'>Legeleistng</div>
                    <div class='flex flex-row gap-y-1'>
                        <div class='flex flex-col'>
                            <div class='text-xs text-gray-600'>Hennen</div>
                            <div class='w-16'>{fixed(result.lay.dames)}</div>
                        </div>
                        <div class='flex flex-col'>
                            <div class='text-xs text-gray-600'>Eier</div>
                            <div class='w-16'>{fixed(result.lay.eggs, 0 )}</div>
                        </div>
                    </div>
                </div>
                <div class='flex flex-col gap-y-1'>
                    <div class='text-xs text-gray-600'>Brutleistung</div>
                    <div class='flex flex-row gap-y-1'>
                        <div class='flex flex-col'>
                            <div class='text-xs text-gray-600'>Eier</div>
                            <div class='w-16'>{fixed(result.brood.eggs)}</div>
                        </div>
                        <div class='flex flex-col'>
                            <div class='text-xs text-gray-600'>Befruchtet</div>
                            <div class='w-16'>{fixed( result.brood.fertile, 1, 100 )}%</div>
                        </div>
                        <div class='flex flex-col'>
                            <div class='text-xs text-gray-600'>Geschlüpft</div>
                            <div class='w-16'>{fixed(result.brood.hatched, 1, 100)}%</div>
                        </div>
                    </div>
                </div>
                <div class='flex flex-col gap-y-1'>
                    <div class='text-xs text-gray-600'>Schauleistung</div>
                    <div class='flex flex-row gap-y-1'>
                        <div class='flex flex-col'>
                            <div class='text-xs text-gray-600'>Zahl</div>
                            <div class='w-16'>{fixed(result.show.count)}</div>
                        </div>
                        <div class='flex flex-col'>
                            <div class='text-xs text-gray-600'>Bewertung</div>
                            <div class='w-16'>{fixed(result.show.score, 1)}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {/if}
</div>

<style>
    .clickable {
        @apply cursor-pointer;
    }
</style>
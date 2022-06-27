<script>
    import { onMount } from 'svelte';
    import api from '../scripts/api.js';

    import Dialog from './Dialog.svelte';
    import InputBreed from './pair/InputBreed.svelte';
    import InputBroodsChicken from './pair/InputBroodsChicken.svelte';
    import InputBroodsPigeon from './pair/InputBroodsPigeon.svelte';
    import InputLay from './pair/InputLay.svelte';
    import InputPair from './pair/InputPair.svelte';
    import InputParents from './pair/InputParents.svelte';
    import InputShow from './pair/InputShow.svelte';

    export let promise;
    let pair = null;// = { section:null, breed:null, color:null };

    // to improve updates, set on promise response in onMount
    let breed = null;
    let parents = null;
    let lay = null;
    let broods = null;
    let show = null;

    let result = {
        lay:   { dames:null, eggs:null },
        brood: { eggs:null, fertile:null, hatched:null },
        show:  { count:null, score:null }
    }

    let disabled=true;
    let valid = false;

    let dialog = false;

    $: calculateLay( parents, lay );
    $: calculateBrood( broods );
    $: calculateShow( show );

    $: validate( pair, breed, parents );

    onMount( () => {
        promise.then( data => {
            pair = data;
            breed = pair.breed;
            parents = pair.parents;
            lay = pair.lay;
            broods = pair.broods;
            show = pair.show;
        });
    });

    const on = {
        dialog: {
            close: () => {
                dialog = false;
            }
        }
    }

    function validate( ...a ) {
        if( pair ) {
            //console.log( a );
            valid = true;
            valid &= pair.name!== null && pair.year > 1800 && pair.year < 2999;
            if ( breed.sectionId && breed.sectionId === 5) {
                console.log('Pigeons');
                valid &= breed.breedId !== null;
                valid &= parents.length === 2 && parents[0].ring.year>0 && parents[0].ring.code>'' && parents[1].ring.year>0 && parents[1].ring.code>'';
            } else {
                console.log('Chickens', breed.colorId, parents.length, parents[0].ring.year, parents[0].ring.code, parents[1].ring.year, parents[1].ring.code);
                valid &= breed.colorId !== null;
                valid &= parents.length >= 2 && parents[0].ring.year>0 && parents[0].ring.code>'' && parents[1].ring.year>0 && parents[1].ring.code>'';
            }

            console.log('Valid', valid);
        }
    }

    function validPair( pair, breed ) {
        return pair && pair.breederId && pair.year >= 0 && pair.year <= 2999 && pair.name &&
            breed && breed.sectionId && breed.breedId && ( breed.colorId || breed.sectionId === 5 )
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
                        console.log( 'Lay', result.lay );
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

    function onEdit() {
        disabled = ! disabled;
    }

    function fixed( number, decimals=0, factor=1  ) {
        if( number === null ) {
            return '';
        } else {
            return (factor * number).toFixed( decimals );
        }
    }

    function send( event ) {
        disabled=true; // stop edit and resend
        console.log('Sen pair ', pair, disabled);
        if( pair.id === 0 ) {
            console.log( 'post' );
            api.pair.post(pair).then(id => {
                pair.id = id;
                console.log( 'NID', pair.id );
            }).catch(error => {
                console.log(error)
            });
        } else {
            console.log( 'put' );
            api.pair.put(pair).then(id => {
                pair.id = id;
            }).catch(error => {
                console.log(error)
            });
        }
    }

</script>

<div class='relative w-full h-full flex flex-col rounded whitespace-nowrap'>
    <h3 class=''>Meldung {valid?'ok':'error'} <span on:click={onEdit}>Edit</span></h3>
    {#if pair }
        <form on:submit|preventDefault={send}>

            <InputPair bind:pair={pair} {disabled}/>

            <InputBreed bind:breed={breed} disabled={disabled}/>

            <InputParents parents={pair.parents} disabled={disabled || !validPair(pair, breed)}/>

            {#if breed.sectionId!==5 }
                <InputLay bind:lay={pair.lay} {disabled}/>
            {/if}


            {#if breed.sectionId === 5 }
                <InputBroodsPigeon bind:broods={pair.broods} {disabled} />
            {:else}
                <InputBroodsChicken bind:broods={pair.broods} {disabled} />
            {/if}

            <InputShow bind:show={pair.show} {disabled} />

            <div>
                <div class='flex-flex-col'>
                    <div class='text-xs text-gray-600'>Notizen</div>
                    <textarea class='w-full h-16' bind:value={pair.notes} {disabled}></textarea>
                </div>
            </div>


            {#if !disabled && valid}
                <div class='flex flex-col align-right'>
                    <button type='submit' {disabled}>Speichern</button>
                </div>
            {/if}



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
        </form>
    {/if}
    {#if dialog}<Dialog on:close={on.dialog.close}/>{/if}
</div>

<style>
    .clickable {
        @apply cursor-pointer;
    }
</style>
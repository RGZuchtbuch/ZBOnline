<script>

    import {meta} from "tinro";
    import api from "../../js/api.js";
    import {user} from "../../js/store.js";

    import NumberInput from '../common/input/Number.svelte';
    import Page from "../common/Page.svelte";
    import TextInput from '../common/input/Text.svelte';
    import TextArea from "../common/input/TextArea.svelte";
    import Color from "./Color.svelte";

    export let params;

    let sectionId;
    let section;
    let breedId;
    let breed;
    let colorId;

    let invalids = {};
    let disabled = breedId !== 0; // enabled if new breeder
    let changed = false; // signal is changed so save required !
    let needFocus = true;

    let route = meta();

    function update( params ) { // whenever the url changes and so the prams
        sectionId = Number( params.sectionId );
        breedId = Number( params.breedId );
        colorId = Number( params.colorId );
    }

    function onToggleColorEdit() {
        console.log( 'edit' );
        disabled = ! disabled;
        needFocus = true;
    }



    function onChange(event) {
        changed = true;
//        invalids.layDames     = result.layDames !== null && ( result.layDames < 1 || result.layDames > 999999 );

        invalids.name       = breed.name === null || breed.name.length < 3;
        invalids.lay        = breed.lay !== null && ( breed.lay < 1 || breed.lay > 366 );
        invalids.layWeight  = breed.layWeight !== null && ( breed.layWeight < 1 || breed.layWeight > 9999 );
        invalids.sireWeight = breed.sireWeight !== null && ( breed.sireWeight < 1 || breed.sireWeight > 99999 );
        invalids.dameWeight = breed.dameWeight !== null && ( breed.dameWeight < 1 || breed.dameWeight > 99999 );
        invalids.sireRing   = breed.sireRing !== null && ( breed.sireRing < 1 || breed.sireRing > 99 );
        invalids.dameRing   = breed.dameRing !== null && ( breed.dameRing < 1 || breed.dameRing > 99 );

        invalids.form = false; // totals
        for( let invalid in invalids ) {
            console.log( invalid, invalids[ invalid ] );
            if( invalids[ invalid ] ) {
                invalids.form = true;
            }
        }
        invalids = invalids; // trigger
        console.log( 'OnBreedFormChange', invalids.form, invalids.lay );
    }

    function onSubmit(event) {
        console.log( 'Submit Breed');
        disabled = true;
        api.breed.post( breed ).then( response => {
            breed.id = response.id;
            changed = false;
        })
    }

    function loadSection( id ) {
        api.section.get( id ).then( response => {
            section = response.section;
        })
    }

    function loadBreed( id ) {
        if( id === 0 ) {
            breed = {
                id:0, name:null, sectionId:sectionId,
                broodGroup:null, // pigeons
                lay:null, layWeight:null, // layers
                sireRing:null, dameRing:null, sireWeight:null, dameWeight:null,
                info:null
            }
        } else {
            api.breed.get(id).then(response => {
                breed = response.breed;
            });
        }
    }

    console.log( 'P', params, colorId );

    $: update( params );
    $: loadSection( sectionId );
    $: loadBreed( breedId );
</script>

{#if section && breed }

    <Page>
        <div slot='title'> Geflügelrasse {breed.name} </div>

        <div slot='header' class='flex flex-row'>
            <div class='grow'>Rasse- und Farbschlägendaten</div>
            {#if $user && $user.admin }
                <div class='w-6 h-6 border-2 border-alert rounded bg-white align-middle text-center text-red-600 cursor-pointer'
                     class:disabled on:click={onToggleColorEdit} title='Daten ändern'>&#9998;</div>
            {/if}
        </div>



        <div slot='body' class='flex flex-col gap-2'>
            <div class='flex flex-row justify-evenly gap-2'>
                <form class='grow flex flex-col border border-gray-600 rounded' on:change={onChange}>
                    <div class='bg-header text-white text-center'>Rassebeschreibung</div>
                    <fieldset class='p-2' {disabled}>

                        <TextInput class='w-full' label={'Name der Rasse'} bind:value={breed.name} error='Pflichtfeld' invalid={invalids.name} required/>

                        <div class='flex flex-col gap-x-4'>

                                {#if section.layers}
                                    <div class='flex flex-row gap-x-2'>
                                        <NumberInput class='w-20' label={'Legeleistung'} bind:value={breed.lay} min=1 max=366 error='1..366' invalid={invalids.lay} />
                                        <NumberInput class='w-20' label={'Eiergewicht g.'} bind:value={breed.layWeight} min=1 max=9999 error='1..9999' invalid={invalids.layWeight} />
                                    </div>

                                {:else}

                                {/if}
                                <div class='flex flex-row gap-x-2'>
                                    <NumberInput class='w-20' label={'Gewicht ♂ g.'} bind:value={breed.sireWeight} min=1 max=99999 error='1..99999' invalid={invalids.sireWeight} />
                                    <NumberInput class='w-20' label={'Gewicht ♁ g.'} bind:value={breed.dameWeight} min=1 max=99999 error='1..99999' invalid={invalids.dameWeight} />
                                </div>
                                <div class='flex flex-row gap-x-2'>
                                    <NumberInput class='w-20' label={'Ring ♂'} bind:value={breed.sireRing} min=1 max=99 error='1..99' invalid={invalids.sireRing} />
                                    <NumberInput class='w-20' label={'Ring ♁'} bind:value={breed.dameRing} min=1 max=99 error='1..99' invalid={invalids.dameRing} />
                                </div>

                                {#if ! disabled}
                                    <TextArea label='Info' bind:value={breed.info} />
                                {/if}
                                <div>{@html breed.info}</div>
                                <div class='h-4'></div>

                        </div>

                        {#if ! disabled && changed && ! invalids.form }
                            <div class='bg-alert text-center font-bold text-white cursor-pointer' on:click={onSubmit}>&#10004; Speichern</div>
                        {/if}
                    </fieldset>
                </form>

                <div class='w-64 flex flex-col gap-2'>
                    <img class='border border-gray-400 rounded' src='assets/breeds/7972.png' />

                    <div class='flex flex-col border border-gray-600 rounded'>
                        <div class='bg-header text-white text-center'>Farbenschläge</div>
                        <div class='flex-flex-col'>
                            {#each breed.colors as color}
                                <div class='mx-2 pl-2 border-b border-gray-300'> → <a href={'standard/sparte/'+sectionId+'/rasse/'+breedId+'/farbe/'+color.id}> {color.name} </a> </div>
                            {/each}
                        </div>
                    </div>
                </div>

            </div>

            {#if colorId}
                <Color {colorId} />
            {/if}

        </div>


    </Page>
{/if}

<style>
    .disabled {
        @apply text-green-600;
    }
</style>
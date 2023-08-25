<script>

    import {afterUpdate, onMount} from 'svelte';
    import {active, meta, router, Route} from 'tinro';

    import {user} from "../../js/store.js";
    import api from '../../js/api.js';

    import Page from "../common/Page.svelte";
    import ReportBreed from './Breed.svelte';
    import ReportBroods from './Broods.svelte';
    import ReportLay from './Lay.svelte';
    import ReportNotes from './Notes.svelte';
    import ReportElders from './Elders.svelte';
    import ReportShow from './Show.svelte';
    import ReportHead from "./Head.svelte";

//    export let id;
    export let params;

    let pair = null;
    let layer = true;

    let disabled = true;

    const invalids = {
        head:false, breed:false, elders:false, lay:false, broods:false, show:false, notes:false
    }

    function newReport( districtId, breederId )  {
        return {
            id: 0, t:9,
            breederId:breederId, districtId:districtId, year: new Date().getFullYear(), group: 'I',
            sectionId: 4, breedId: 1024, colorId: 8543,
            name: 'A', paired: '2021-12-31', notes: 'Test',
            parents: [],
            lay: { id:0, reportId:0, start:null, end:null, eggs:null, weight:null},
            broods: [],
            show: { 89:null, 90:null, 91:null, 92:null, 93:null, 94:null, 95:null, 96:null, 97:null },
            changed: false, invalid: false,
        };
    }

    function onToggleEdit() {
        disabled = ! disabled;
    }

    function onSubmit() {
        pair.changed = false;
        disabled = true;
        api.pair.post( pair )
            .then( response => {
                pair.id = response.id;
                pair = pair;
            });
    }

    function loadPair( id ) {
        if( id ) { // existing
            api.pair.get( id ).then(response => {
                pair = response.pair;
                if( pair.changed === undefined ) pair.changed = false;
                if( pair.invalid === undefined ) pair.invalid = false;
                console.log( 'Pair', pair );
            });
        } else { // new
            pair = newReport( Number( params.districtId ), Number( params.breederId ) );
        }
    }

    function update( params ) {
        loadPair( params.pairId ); // async
    }

    function onInput(event ) {
        pair.changed = true;

        // count sires and dames
        if( pair && pair.parents && pair.lay ) {
            pair.lay.sires = 0;
            pair.lay.dames = 0;
            for( let parent of pair.parents ) {
                if( parent.ring ) {
                    if (parent.sex === '1.0') {
                        pair.lay.sires++;
                    } else {
                        pair.lay.dames++;
                    }
                }
            }

            layer = pair.sectionId !== 5;
            pair = pair; // trigger
        }

        validate()
    }

    function validate() {
        pair.invalid = false;
        for( const key in invalids ) {
            pair.invalid |= invalids[ key ];
        }
        console.log( 'Invalid', pair.invalid);
    }


    onMount( () => {
        console.log( 'Mount', pair );
    })

    $: update( params );

</script>

{#if pair}
<Page>
    <div slot='title'> Zuchtbuch Meldung</div>
        <div slot='header' class='flex flex-row'>
            <div class='grow'>Stamm / Paar Meldung {pair.changed}:{pair.invalid}</div>
            {#if $user && ( $user.admin || $user.moderator.includes( pair.districtId ) ) }
                <div class='w-6 border rounded text-center text-red-600 cursor-pointer' class:disabled on:click={onToggleEdit} title='Daten ändern'>&#9998;</div>
            {/if}
        </div>


        <form slot='body' class='bg-gray-100' on:input={onInput} on:submit|preventDefault={onSubmit}>

            <fieldset class='flex flex-col gap-1' {disabled}>

                <ReportHead {pair} bind:invalid={invalids.head} {disabled}/>

                <ReportBreed {pair} bind:invalid={invalids.breed} {disabled}/>

                {#if pair.sectionId }

                    <ReportElders {pair} bind:invalid={invalids.elders} {disabled}/>

                    {#if pair.sectionId !== 5}
                        <ReportLay {pair} bind:invalid={invalids.lay} {disabled}/>
                    {/if}

                    <ReportBroods {pair} bind:invalid={invalids.broods} {disabled} />

                    <ReportShow {pair} bind:invalid={invalids.show} {disabled} />

                    <ReportNotes bind:notes={pair.notes} bind:invalid={invalids.notes} {disabled} />

                {/if}


                {#if ! disabled}
                    {#if pair.changed && ! pair.invalid }
                        <button type='submit' class='rounded border bg-alert text-center text-white cursor-pointer'>Meldung speichern</button>
                    {:else}
                        <button type='submit' class='rounded border bg-gray-400 text-center text-white cursor-pointer' disabled>Kann (noch) nicht speichern</button>
                    {/if}
                {/if}

            </fieldset>
        </form>
    </Page>
{/if}

<style>
    .disabled {
        @apply text-white;
    }
</style>
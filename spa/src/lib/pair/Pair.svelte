<script>

    import {afterUpdate, onMount} from 'svelte';
    import { slide } from 'svelte/transition';
    import dic from '../../js/dictionairy.js';
    import {active, meta, router, Route} from 'tinro';

    import {user} from "../../js/store.js";
    import api from '../../js/api.js';

    import Page from "../common/Page.svelte";
    import Form from '../common/form/Form.svelte';
    import FormStatus from '../common/form/Status.svelte';
    import PairHead from "./Head.svelte";
    import PairBreed from './Breed.svelte';
    import PairBroods from './Broods.svelte';
    import PairLay from './Lay.svelte';
    import PairElders from './Parents.svelte';
    import PairShow from './Show.svelte';
    import PairNotes from './Notes.svelte';

    //import {isNumber} from "chart.js/helpers";

//    export let id;
    export let params;

    let pair = null;
    let changed = false;
    let invalid = false;
    let layer = true;

    let disabled = true;

    const invalids = {
        head:false, breed:false, elders:false, lay:false, broods:false, show:false, notes:false
    }

    function newPair( districtId, breederId )  {
        return {
            id: 0, t:9,
            breederId:breederId, districtId:districtId, year: new Date().getFullYear(), group: 'I',
            sectionId: 4, breedId: 1024, colorId: 8543,
            name: 'A', paired: '2023-01-01', notes: 'Notiezen :',
            parents: [],
            lay: { id:0, pairId:0, start:null, end:null, eggs:null, weight:null},
            broods: [],
            show: { 89:null, 90:null, 91:null, 92:null, 93:null, 94:null, 95:null, 96:null, 97:null },
            breeder: { firstname:null, infix:null, lastname:null },
        };
    }

    function onToggleEdit() {
        disabled = ! disabled;
    }

    function onSubmit() {
        disabled = true;
        if( pair.id > 0 ) {
            api.pair.put( pair.id, pair ).then( response => {
                changed = false;
            });
        } else{
            api.pair.post( pair ).then(response => {
                pair.id = response.id;
                changed = false;
            });
        }
    }

    function loadPair( id ) {
        if( id > 0 ) { // existing, note id could be '0' from param
            api.pair.get( id ).then( response => {
                pair = response.pair;
                changed = false;
                invalid = false;
            });
        } else { // new
            pair = newPair( Number( params.districtId ), Number( params.breederId ) );
            disabled = false; // ready to edit
        }
    }

    function update( params ) {
        loadPair( params.pairId ); // async
    }

    function onInput( event ) { // only for signalling changes, as input is faster that sveltes value update !
        changed = true;
    }

    // TODO should become new form
    function validate() {
        if( pair ) {
            invalid = false;
            for (const key in invalids) {
                invalid |= invalids[key];
            }
        }
    }

    onMount( () => {
    })

    $: validate( invalids );
    $: update( params );

</script>

{#if pair}
    <Page>
        <div slot='title'> Zuchtbuch Meldung</div>

        <div slot='header' class='flex flex-row'>
            <div class='grow' class:invalid>Stamm / Paar Meldung</div>
            {#if $user && ( $user.admin || $user.moderator.includes( pair.districtId ) ) }
                <div class='w-6 border rounded text-center text-red-600 cursor-pointer' class:disabled on:click={onToggleEdit} title='Daten ändern'>&#9998;</div>
            {/if}
        </div>

        <div slot='body' class='pl-4' transition:slide>
            <Form>
                <div class='flex'>
                    <div>Züchter Meldung ändern</div>
                    <FormStatus />
                </div>


                <PairHead bind:pair={pair} bind:invalid={invalids.head} {disabled}/>

                <PairBreed bind:pair={pair} bind:invalid={invalids.breed} {disabled}/>

                {#if pair.sectionId }

                    <PairElders bind:pair={pair} bind:invalid={invalids.elders} {disabled}/>

                    {#if pair.sectionId !== 5}
                        <PairLay bind:pair={pair} bind:invalid={invalids.lay} {disabled}/>
                    {/if}

                    <PairBroods bind:pair={pair} bind:invalid={invalids.broods} {disabled} />

                    <PairShow bind:pair={pair} bind:invalid={invalids.show} {disabled} />

                    <PairNotes bind:notes={pair.notes} bind:invalid={invalids.notes} {disabled} />

                {/if}


                {#if ! disabled}
                    {#if changed && ! invalid }
                        <button type='submit' class='rounded border bg-alert text-center text-white cursor-pointer'>Meldung speichern</button>
                    {:else}
                        <button type='submit' class='rounded border bg-gray-400 text-center text-white cursor-pointer' disabled>Kann (noch) nicht speichern</button>
                    {/if}
                {/if}
            </Form>
        </div>
    </Page>
{/if}

<style>
    .disabled {
        @apply text-white;
    }
    .invalid {
        @apply text-red-800;
    }
</style>
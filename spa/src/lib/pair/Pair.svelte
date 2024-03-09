<script>

    import {afterUpdate, onMount} from 'svelte';
    import { slide } from 'svelte/transition';
    import dic from '../../js/dictionairy.js';
    import {active, meta, router, Route} from 'tinro';
    import validator from '../../js/validator.js';

    import {user} from "../../js/store.js";
    import api from '../../js/api.js';

    import Page from "../common/Page.svelte";
    import Form from '../common/form/Form.svelte';
    import FormStatus from '../common/form/Status.svelte';
    import PairHead from "./Head.svelte";
    import PairBroods from './Broods.svelte';
    import PairLay from './Lay.svelte';
    import PairParents from './Parents.svelte';
    import PairShow from './Show.svelte';
    import PairNotes from './Notes.svelte';
    import {newBreed} from "../../js/template.js";

    //import {isNumber} from "chart.js/helpers";

//    export let id;
    export let id = 0;
    export let districtId = null;
    export let breederId = null;

    let pair = null;
    let breeder = null;
    let changed = false;
    let invalid = false;
    let layer = true;

    let disabled = true;

    const route = meta();

    function newPair()  {
        console.log( 'From route', districtId, breederId );
        return {
            id: 0, t:9,
            breederId:breederId, districtId:districtId, year: new Date().getFullYear(), group: 'I',
            sectionId: 4, breedId: 1024, colorId: 8543,
            name: 'Neu', paired: '2023-01-01', notes: 'Info...',
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

    function updatePair( districtId, breederId, id ) {
        if( id > 0 && breederId > 0 ) { // existing, note id could be '0' from param
            api.pair.get( id ).then( response => {
                pair = response.pair;
                api.breeder.get( pair.breederId ).then( response => {
                    pair.breeder = response.breeder;
                });
            });
        } else { // new
            pair = newPair();
            api.breeder.get( pair.breederId ).then( response => {
                pair.breeder = response.breeder;
            });
            disabled = false; // ready to edit
        }
    }


    onMount( () => {
    })

//    $: validate( invalids );
    $: updatePair( districtId, breederId, id );


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


                <PairHead bind:pair={pair}/>

                {#if pair.sectionId && pair.breedId && ( pair.colorId || pair.sectionId === 5 ) }
                    <div  transition:slide>

                        <PairParents bind:pair={pair} />
                        {#if pair.sectionId !== 5} <!-- Layers -->
                            <PairLay bind:pair={pair} />
                        {/if}
                        <PairBroods bind:pair={pair} />
                    <!--

                        <PairShow bind:pair={pair} />
                     -->
                    </div>
                {/if}
                <PairNotes bind:notes={pair.notes} {disabled} />


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
<script>

    import {beforeUpdate, afterUpdate, onMount} from 'svelte';
    import { slide } from 'svelte/transition';
    import dic from '../../../js/dictionairy.js';
    import {active, meta, router, Route} from 'tinro';
    import validator from '../../../js/validator.js';

    import {user} from "../../../js/store.js";
    import api from '../../../js/api.js';

    import Page from "../../common/Page.svelte";
    import Form from '../../common/form/Form.svelte';
    import FormStatus from '../../common/form/Status.svelte';
    import PairHead from "../Head.svelte";
    import PairBroods from '../Broods.svelte';
    import PairLay from '../Lay.svelte';
    import PairParents from '../Parents.svelte';
    import PairShow from '../Show.svelte';
    import PairNotes from '../Notes.svelte';

    export let id = 0; // pairId
    export let districtId = null;
    export let breederId = null;

    let formType = null; // pigeons vs layers

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
            id: 0,
            breederId:breederId, districtId:districtId, year: new Date().getFullYear(), group: 'I',
            sectionId: 12, breedId: 1024, colorId: 8543,
            name: 'Neu', paired: '2023-01-01', notes: 'Info...',
            parents: [],
            lay: { start:null, end:null, eggs:null, weight:null },
            broods: [],
            show: { 89:null, 90:null, 91:null, 92:null, 93:null, 94:null, 95:null, 96:null, 97:null },
            breeder: { firstname:null, infix:null, lastname:null },
        };
    }

    function onToggleEdit() {
        disabled = ! disabled;
    }

    function onSubmit() {
        //disabled = true;
        // TODO check on delete like by having a checkbox for delete
        if( pair.id > 0 ) { // existing
            api.pair.put( pair.id, pair ).then( response => {
                changed = false;
            });
        } else { // new
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

    function selectForm( type ) {
        return ( event ) => {
            formType = type;
            console.log( 'Form ', formType );
        }
    }


    onMount( () => {
        console.log( 'Maount', id);
    })

    beforeUpdate( () => {
        console.log( 'Before', id);
    })
    afterUpdate( () => {
        console.log( 'After', id);
    })

//    $: validate( invalids );
    $: updatePair( districtId, breederId, id );


    console.log( 'Boe', id);

</script>

{#if pair}
    <Page>
        <div slot='title'> Zuchtbuch Meldung</div>

        <div slot='header' class='flex flex-col'>
            <div class='flex'>
                <div class='grow' class:invalid>Stamm / Paar Meldung</div>
                {#if $user && ( $user.admin || $user.moderator.includes( pair.districtId ) ) }
                    <div class='w-6 border rounded text-center text-red-600 cursor-pointer' class:disabled on:click={onToggleEdit} title='Daten ändern'>&#9998;</div>
                {/if}
            </div>

        </div>

        <div slot='body' class='pl-4' transition:slide>
            {#if id === 0 && formType == null }
                <div class='flex flex-row justify-evenly' transition:slide>
                    <figure class='cursor-pointer'>
                        <img class='w-16' src='/assets/layer.png' on:click={selectForm(LAYERS)} />
                        <figcaption class='text-center'>Leger</figcaption>
                    </figure>
                    <figure class='cursor-pointer'>
                        <img class='w-16' src='/assets/pigeon.png' on:click={selectForm(PIGEONS)} />
                        <figcaption class='text-center'>Tauben</figcaption>
                    </figure>
                </div>
            {:else if formType === PIGEONS} <!-- Pigeons form -->

                <Form {disabled} on:submit={onSubmit}>
                    <div class='flex'>
                        <div>Züchter Meldung ändern</div>
                        <FormStatus />
                    </div>

                    <PairHead bind:pair={pair}/>

                    {#if pair.sectionId && pair.breedId && ( pair.colorId || pair.sectionId === 5 ) }
                        <div  transition:slide>
                            <PairParents bind:pair={pair} />
                            <PairBroods bind:pair={pair} />
                            <PairShow bind:pair={pair} />
                        </div>
                    {/if}
                    <PairNotes bind:notes={pair.notes} {disabled} />
                </Form>

            {:else} <!-- Layers form -->
                <Form {disabled} on:submit={onSubmit}>
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
                            <PairShow bind:pair={pair} />
                        </div>
                    {/if}
                    <PairNotes bind:notes={pair.notes} {disabled} />
                </Form>
            {/if}
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
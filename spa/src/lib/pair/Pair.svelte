<script>

    import {beforeUpdate, afterUpdate, onMount} from 'svelte';
    import { slide } from 'svelte/transition';
    import dic from '../../js/dictionairy.js';
    import {active, meta, router, Route} from 'tinro';
    import validator from '../../js/validator.js';

    import {user} from "../../js/store.js";
    import api from '../../js/api.js';

    import Page from '../common/Page.svelte';

    import Form from '../common/form/Form.svelte';
    import FormStatus from '../common/form/Status.svelte';
    import PairHead from "./Head.svelte";
    import PairBroods from './Broods.svelte';
    import PairLay from './Lay.svelte';
    import PairParents from './Parents.svelte';
    import PairShow from './Show.svelte';
    import PairNotes from './Notes.svelte';

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
            sectionId: null, breedId: null, colorId: null,
            name: null, paired: null, notes: 'Info...',
            parents: [],
            lay: { start:null, end:null, eggs:null, weight:null },
            broods: [],
            show: { 89:null, 90:null, 91:null, 92:null, 93:null, 94:null, 95:null, 96:null, 97:null },
            breeder: { firstname:null, infix:null, lastname:null },
            delete: false,
        };
    }

    function onToggleEdit() {
        disabled = ! disabled;
    }

    function onSubmit() {
        //disabled = true;
        // TODO check on delete like by having a checkbox for delete
        if( pair.name ) {
            if (pair.id > 0) { // existing
                api.pair.put(pair.id, pair).then(response => {
                    changed = false;
                });
            } else { // new
                api.pair.post(pair).then(response => {
                    pair.id = response.id;
                    changed = false;
                });
            }
        } else if( pair.delete ) { // and not pair.name
            console.log('Delete' );
            if( pair.id > 0 ) {
                api.pair.delete(pair.id).then(response => {
                    if (response.success) {
                        pair.id = null;
                        router.goto(route.from); // away from deleted pair
                    }
                });
            }
        }
    }


    function updatePair( url ) {
        console.log( 'Update Pair' );
        if( id > 0 && breederId > 0 && districtId > 0 ) { // existing, note id could be '0' from param for new
            api.pair.get( id ).then( response => {
                pair = response.pair;
                pair.delete = false;
                formType = pair.sectionId === 5 ? PIGEONS : LAYERS;
                api.breeder.get( pair.breederId ).then( response => {
                    pair.breeder = response.breeder;
                });
            });
        } else { // new
            pair = newPair();
            formType = null;
            api.breeder.get( pair.breederId ).then( response => {
                pair.breeder = response.breeder;
            });
            disabled = false; // ready to edit
        }
    }

    function setFormType( type ) {
        return ( event ) => {
            formType = type;
            console.log( 'Form ', formType );
        }
    }

    $: updatePair( $router.url );

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

        <div slot='body' class='' transition:slide>
            {#if formType === null }
                <div class='flex flex-row justify-evenly' transition:slide>
                    <button type='button' on:click={ setFormType( LAYERS ) } >
                        <figure class='cursor-pointer' title={dic.title.layers}>
                            <img class='w-16' src='/assets/layer.png'  alt={dic.alt.layers}/>
                            <figcaption class='text-center'>Leger</figcaption>
                        </figure>
                    </button>
                    oder
                    <button type='button' on:click={ setFormType( PIGEONS ) } >
                        <figure class='cursor-pointer' title={dic.title.pigeons}>
                            <img class='w-16' src='/assets/pigeon.png' alt={dic.alt.pigeons}/>
                            <figcaption class='text-center'>Tauben</figcaption>
                        </figure>
                    </button>
                </div>
            {:else if formType === LAYERS} <!-- Pigeons form -->
                <Form {disabled} class='gap-y-1' on:submit={onSubmit}>
                    <div class='flex justify-end'>
                        <FormStatus />
                    </div>
                    <div class='flex flex-col gap-y-1' >
                        <PairHead {formType} bind:pair={pair}/>
                        {#if pair.sectionId && pair.breedId && pair.colorId }
                            <div  transition:slide>
                                <PairParents bind:pair={pair} />
                                <PairLay bind:pair={pair} />
                                <PairBroods bind:pair={pair} />
                                <PairShow bind:pair={pair} />
                            </div>
                        {/if}
                        <PairNotes bind:notes={pair.notes} {disabled} />
                    </div>
                </Form>
            {:else if formType === PIGEONS} <!-- Pigeons form -->
                <Form {disabled} on:submit={onSubmit}>
                    <div class='flex justify-end'>
                        <FormStatus />
                    </div>
                    <div class='flex flex-col gap-y-1' >
                        <PairHead {formType} bind:pair={pair}/>
                        {#if pair.sectionId && pair.breedId  }
                            <div  transition:slide>
                                <PairParents bind:pair={pair} />
                                <PairBroods bind:pair={pair} />
                                <PairShow bind:pair={pair} />
                            </div>
                        {/if}
                        <PairNotes bind:notes={pair.notes} {disabled} />
                    </div>
                </Form>
            {:else}
                ??
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
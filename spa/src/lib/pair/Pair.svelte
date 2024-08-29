<script>

    import {beforeUpdate, afterUpdate, onMount, getContext, setContext} from 'svelte';
    import { slide } from 'svelte/transition';
    import dic from '../../js/dictionairy.js';
    import {active, meta, router } from 'tinro';
    import validator from '../../js/validator.js';

    import {user} from "../../js/store.js";
    import api from '../../js/api.js';
    import { toNumber } from '../../js/util.js';

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
    //export let districtId = null;
    //export let breederId = null;

    let district = getContext( 'district' );
    let breeder = getContext( 'breeder' );

    let pair = null;
//    let breeder = null;
    let changed = false;
    let invalid = false;
    let layer = true;

    let disabled = true;

    const route = meta();

    function newPair()  {
        console.log( 'From route', $district.id, $breeder.id );
        if( $district && $breeder ) {
            return {
                id: 0,
                breederId: $breeder.id, districtId: $district.id, year: new Date().getFullYear(), group: 'I',
                sectionId: null, breedId: null, colorId: null,
                name: null, paired: null, notes: 'Info...',
                parents: [],
                lay: {start: null, end: null, eggs: null, weight: null},
                broods: [],
                show: {89: null, 90: null, 91: null, 92: null, 93: null, 94: null, 95: null, 96: null, 97: null},
                breeder: {firstname: null, infix: null, lastname: null},
                delete: false,
            };
        }
        return null; // should not happen
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
                        id = null;
                        pair.id = null;
                        router.goto(route.from); // away from deleted pair
                    }
                });
            }
        }
    }


    function updatePair( id ) {
        id = toNumber( id ); // null if not
        if( id && id > 0 ) { // existing, note id could be '0' from param for new
            api.pair.get( id ).then( response => {
                pair = response.pair;
                pair.delete = false;
                api.breeder.get( pair.breederId ).then( response => {
                    pair.breeder = response.breeder;
                    breeder.set( pair.breeder );
                });
            });
        } else { // new as id == 0 or null
            pair = newPair();
            api.breeder.get( pair.breederId ).then( response => {
                pair.breeder = response.breeder;
                breeder.set( pair.breeder );
            });
            disabled = false; // ready to edit
        }
    }

    $: updatePair( id );

</script>

{#if pair}
    <Page>
        <div slot='title' class='flex flex-row justify-between'>

            <a class='border-0 w-4' title='Zu den Meldungen' href={$router.from}>←</a>

            <div>Zuchtbuch Meldung</div>
            {#if $user && ( $user.admin || $user.moderator.includes( pair.districtId ) ) }
                <div class='w-6 border rounded text-center text-red-600 cursor-pointer' class:disabled on:click={onToggleEdit} title='Daten ändern'>&#9998;</div>
            {/if}
        </div>

        <div slot='header' class='flex flex-col'>
            <div class='flex'>
                <div class='grow text-center' class:invalid>Stamm / Paar Meldung</div>
            </div>

        </div>

        <div slot='body' class='' transition:slide>
            <Form {disabled} class='gap-y-1' on:submit={onSubmit}>
                <div class='flex justify-end'>
                    <FormStatus />
                </div>
                <div class='flex flex-col gap-y-1' >
                    <PairHead bind:pair={pair}/>

                    {#if pair.sectionId && pair.breedId && (pair.sectionId === PIGEONS || pair.colorId )}
                        {#if pair.sectionId === PIGEONS}
                            <div  transition:slide>
                                <PairParents bind:pair={pair} />
                                <PairBroods bind:pair={pair} />
                                <PairShow bind:pair={pair} />
                            </div>
                        {:else}
                            <div  transition:slide>
                                <PairParents bind:pair={pair} />
                                <PairLay bind:pair={pair} />
                                <PairBroods bind:pair={pair} />
                                <PairShow bind:pair={pair} />
                            </div>
                        {/if}
                    {/if}
                    <PairNotes bind:notes={pair.notes} {disabled} />
                </div>
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
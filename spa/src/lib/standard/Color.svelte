<script>

    import {meta} from "tinro";
    import api from "../../js/api.js";
    import {user} from "../../js/store.js";
    import {txt} from '../../js/util.js';

    import NumberInput from '../common/input/Number.svelte';
    import Page from "../common/Page.svelte";
    import TextInput from '../common/input/Text.svelte';
    import TextArea from "../common/input/TextArea.svelte";
    import CheckInput from "../common/input/CheckInput.svelte";

    export let params;

    let colorId;
    let color;

    let invalids = {};

    let disabled = colorId !== 0; // enabled if new breeder
    let changed = false;
    let needFocus = true;

    let route = meta();

    function onToggleEdit() {
        console.log( 'edit' );
        disabled = ! disabled;
        needFocus = true;
    }

    function onChange(event) {
        changed = true;
//        invalids.layDames     = result.layDames !== null && ( result.layDames < 1 || result.layDames > 999999 );

        invalids.name       = color.name === null || color.name.length < 3;

        invalids.form = false; // totals
        for( let invalid in invalids ) {
            console.log( invalid, invalids[ invalid ] );
            if( invalids[ invalid ] ) {
                invalids.form = true;
            }
        }
        invalids = invalids; // trigger
        console.log( 'OnColorFormChange', invalids.form, invalids.lay );
    }


    function onSubmit(event) {
        console.log( 'Submit Breed');
        disabled = true;
        api.color.post( color ).then( response => {
            color.id = response.id;
            changed = false;
        })
    }


    function loadColor( id ) {
        console.log( 'Id', id );
            if (id == 0) {
                color = {
                    id: 0, name: null, breedId: params.breedId,
                    aoc:false,
                    info: null
                }
            } else {
                api.color.get(id).then(response => {
                    color = response.color;
                });
            }
    }

    console.log( 'P', colorId );

    $: loadColor( params.colorId );
</script>


{#if color }
    <div class='w-full flex flex-col border border-gray-400 rounded'>
        <div class='flex flex-row px-2 py-1 bg-header text-white'>
            <div class='grow text-center'>Farbenschläg {color.name}</div>
            {#if $user && $user.admin }
                <div class='w-6 text-center text-red-600 cursor-pointer'
                     class:disabled on:click={onToggleEdit} title='Daten ändern'>[&#9998;]</div>
            {/if}
        </div>
        <form  class='' on:input={onChange}>
            <fieldset class='flex-flex-col p-2' {disabled}>
                <TextInput class='w-full' label={'Name der Farbe'} bind:value={color.name} error='Pflichtfeld' invalid={invalids.name} required/>


                {#if ! disabled}
                    <CheckInput class='w-16' label='AOC Klasse' bind:value={color.aoc} />
                {/if}
                {#if color.aoc}
                    <div>AOC klasse</div>
                {/if}

                {#if ! disabled}
                    <TextArea label='Info' bind:value={color.info} />
                {/if}
                {#if color.info}
                    <div>{@html txt(color.info)}</div>
                {:else}
                    Farbenschlagbeschreibung steht noch nicht zur Verfügung
                {/if}


                {#if ! disabled && changed && ! invalids.form }
                    <div class='bg-alert text-center font-bold text-white cursor-pointer' on:click={onSubmit}>&#10004; Speichern</div>
                {/if}
            </fieldset>
        </form>
    </div>
{/if}

<style>
    .disabled {
        @apply text-white;
    }
</style>
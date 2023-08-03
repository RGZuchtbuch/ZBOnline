<script>
    import { afterUpdate, onMount } from 'svelte';
    import {txt} from '../../../js/util.js';
    import Button from '../../common/input/Button.svelte';
    import DateInput from '../../common/input/Date.svelte';
    import Select from '../../common/input/Select.svelte';
    import Text from '../../common/input/Text.svelte';
    import api from "../../../js/api.js";
    import TextArea from "../../common/input/TextArea.svelte";

    export let breeder;
    console.log( 'B', breeder )
    let disabled = breeder.id !== null; // enabled if new breeder
    let needFocus = true;
    let clubs = null;
    let changed = false; // form changed
    let invalid = false;

    let focusElement; // to set focus to for starters

    function onToggleEdit() {
        console.log( 'edit' );
        disabled = ! disabled;
        needFocus = true;
    }

    function onChange(event) {
        changed = true;
    }

    function validate() {
        console.log( 'BC', breeder );
        invalid = breeder.firstname === '' || breeder.lastname === '' || breeder.clubId === null || breeder.start === null;
    }

    function onSubmit(event) {
        console.log( 'Submit Breeder account');
        disabled = true;
        api.breeder.post( breeder ).then( response => {
            const id = response.id;
            breeder.id = id;
            changed = false;
        })
    }

    function loadClubs( id ) {
        api.district.clubs.get( id ).then( response => {
            clubs = response.clubs;
        });
    }



    onMount( () => {
        focusElement.focus();
    })
    afterUpdate( () => {
        if( needFocus ) {
            focusElement.focus();
            needFocus = false;
        }
        validate(); // after all changes are in place, was not to in onChange !
    })

    $: loadClubs( breeder.districtId );


</script>

<div class='flex flex-col'>
    {#if breeder }
        <h2 class='w-256 border border-gray-400 rounded-t p-2 bg-header text-center text-xl print'>
            Züchter {txt(breeder.firstname)} {txt(breeder.infix)} {txt(breeder.lastname)} - Daten
        </h2>

        <form class='flex flex-col bg-gray-200 border border-gray-400 rounded-b p-4' on:input={onChange}>
            <fieldset {disabled}>

                <div class='flex gap-x-2'>
                    <Text class='w-64' bind:element={focusElement} bind:value={breeder.firstname} label='Vorname' error='Pflichtfeld' required/>
                    <Text class='w-32' bind:value={breeder.infix} label='Infix'/>
                    <Text class='w-64' bind:value={breeder.lastname} label='Nachname' error='Pflichtfeld' required/>
                    <div class='grow'></div>
                    <div class='border-2 border-gray-400 rounded w-7 h-7 align-middle text-center text-red-600 cursor-pointer' class:disabled on:click={onToggleEdit}>&#9998;</div>
                </div>
                <div class='h-4'></div>

                <div class=''>
                    {#if disabled}
                        <Text class='w-64' label='Ortsverein' value={breeder.clubName} />
                    {:else}
                        <Select class='w-64' label='Ortsverein' bind:value={breeder.clubId} error='Pflichtfeld' required>
                            <option class='bg-white' value={null}> ? </option>
                            {#if clubs}
                                {#each clubs as club}
                                    <option class='bg-white' value={club.id}> {club.name} </option>
                                {/each}
                            {:else}
                                <option class='' value={breeder.clubId} selected> {breeder.clubName} </option>
                            {/if}
                        </Select>{breeder.clubId}
                    {/if}
                </div>

                <div class='flex gap-2'>
                    <DateInput class='w-24' label='ZB-Mitglied seit' bind:value={breeder.start} required/>
                    <DateInput class='w-24' label='bis' bind:value={breeder.end} />

                </div>

                <Text class='' bind:value={breeder.email} label='Email'/>

                <TextArea class='' label='Info' bind:value={breeder.info} />
                {invalid}
                {#if ! disabled && changed && ! invalid }
                    <div class='bg-alert text-center font-bold text-white cursor-pointer' on:click={onSubmit}>Speichern</div>
                {/if}
            </fieldset>
        </form>

    {/if}
</div>

<style>
    .disabled {
        @apply text-green-600;
    }
</style>
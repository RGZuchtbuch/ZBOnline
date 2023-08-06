<script>
    import { afterUpdate, onMount } from 'svelte';
    import {txt} from '../../js/util.js';
    import Button from '../common/input/Button.svelte';
    import DateInput from '../common/input/Date.svelte';
    import Select from '../common/input/Select.svelte';
    import Text from '../common/input/Text.svelte';
    import api from "../../js/api.js";
    import TextArea from "../common/input/TextArea.svelte";
    import Page from "../common/Page.svelte";
    import {user} from "../../js/store.js";

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

    console.log( "U", clubs );
</script>

<Page>
    <div slot='title'> Zuchtbuchmitglied {breeder.firstname} {txt(breeder.infix)} {breeder.lastname}</div>
    <div slot='header' class='flex flex-row'>
        <div class='grow'>Mitgliedsdaten</div>
        {#if $user && ( $user.admin || $user.moderator.includes( breeder.districtId ) ) }
            <div class='w-6 h-6 border-2 border-alert rounded bg-white align-middle text-center text-red-600 cursor-pointer' class:disabled on:click={onToggleEdit} title='Daten ändern'>&#9998;</div>
        {/if}
    </div>

    <form slot='body' class='flex flex-col' on:input={onChange}>
        <fieldset {disabled}>

            <div class='flex gap-x-2'>
                <Text class='w-64' bind:element={focusElement} bind:value={breeder.firstname} label='Vorname' error='Pflichtfeld' required/>
                <Text class='w-32' bind:value={breeder.infix} label='Infix'/>
                <Text class='w-64' bind:value={breeder.lastname} label='Nachname' error='Pflichtfeld' required/>
            </div>
            <div class='h-4'></div>

            <Select class='w-64' label='Ortsverein' bind:value={breeder.clubId} error='Pflichtfeld' >
                <option value={null}> ? </option>
                {#if clubs}
                    {#each clubs as club}
                        <option value={club.id}> {club.name} </option>
                    {/each}
                {:else}
                    <option class='' value={breeder.clubId} selected> {breeder.clubName} </option>
                {/if}
            </Select>

            <div class='flex gap-2'>
                <DateInput class='w-24' label='ZB-Mitglied seit' bind:value={breeder.start} required/>
                <DateInput class='w-24' label='bis' bind:value={breeder.end} />

            </div>

            <Text class='' bind:value={breeder.email} label='Email'/>

            <TextArea class='' label='Info' bind:value={breeder.info} />

            {#if ! disabled && changed && ! invalid }
                <div class='bg-alert text-center font-bold text-white cursor-pointer' on:click={onSubmit}>Speichern</div>
            {/if}
        </fieldset>
    </form>
</Page>

<style>
    .disabled {
        @apply text-green-600;
    }
</style>
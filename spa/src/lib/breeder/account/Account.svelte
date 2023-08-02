<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import {txt} from '../../../js/util.js';
    import Button from '../../common/input/Button.svelte';
    import DateInput from '../../common/input/Date.svelte';
    import Select from '../../common/input/Select.svelte';
    import Text from '../../common/input/Text.svelte';
    import api from "../../../js/api.js";
    import TextArea from "../../common/input/TextArea.svelte";

    export let breeder;

    let clubs = null;
    let changed = false; // form changed

    function onChange(event) {
        console.log( 'changed' );
        changed = true;
    }

    function onSubmit(event) {
        console.log( 'Submit Breeder account');
        changed = false;
        api.breeder.post( breeder ).then( response => {
            const id = response.id;
            breeder.id = id;
        })
    }

    function loadClubs( districtId ) {
        api.district.clubs.get( districtId ).then( response => {
            clubs = response.clubs;
        });
    }

    onMount( () => {
    })

    loadClubs( breeder.districtId );

</script>

<main>
    {#if breeder }
        <h3 class='w-256 text-center'>
            Züchter {txt(breeder.firstname)} {txt(breeder.infix)} {txt(breeder.lastname)} - Daten
        </h3>

        <form class='flex flex-col border border-gray-400 rounded bg-gray-200 m-4 p-2' autocomplete='off' on:input={onChange}>

            <div class='flex gap-2'>
                <Text class='w-64' bind:value={breeder.firstname} label='Vorname' required/>
                <Text class='w-64' bind:value={breeder.infix} label='Infix' required/>
                <Text class='w-64' bind:value={breeder.lastname} label='Nachname' required/>
            </div>
            <div class='h-4'></div>

            <div class='flex gap-2'>
                <Select class='w-64' label='Ortsverein' bind:value={breeder.clubId} required>
                    {#if clubs}
                        {#each clubs as club}
                            <option class='bg-white' value={club.id} selected={club.id === breeder.clubId}> {club.name} </option>
                        {/each}
                    {:else}
                        <option class='' value={breeder.clubId} selected> {breeder.clubName} </option>
                    {/if}
                </Select>

                <DateInput class='w-24' label='Mitglied seit' bind:value={breeder.start} required/>
                <DateInput class='w-24' label='Mitglied bis' bind:value={breeder.end} />
            </div>

            <Text class='' bind:value={breeder.email} label='Email Adresse'/>

            <TextArea class='' label='Info' bind:value={breeder.info} />

            {#if changed}
                <Button class='edit' on:click={onSubmit} label='' value='speichern' />
            {/if}
        </form>

    {/if}
</main>

<style>

</style>
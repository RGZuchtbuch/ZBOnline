<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { txt } from '../../js/util.js';
    import { user } from '../../js/store.js'
//    import Breeders from '../Breeders.svelte';
    import Button from '../input/Button.svelte';
    import Date from '../input/Date.svelte';
    import Select from '../input/Select.svelte';
    import Text from '../input/Text.svelte';

    export let districtId = null;
    export let moderator = null;

    let district = null;
    let breeders = null;
    let clubs = null;

    let allBreeders = false;

    function handle( districtId ) {
        if( districtId ) {
            api.district.get( districtId ).then( response => {
               district = response.district;
            });
            api.district.breeders.get( districtId ).then( response => {
                breeders = response.breeders;
            });
            api.district.clubs.get( districtId ).then( response => {
                clubs = response.clubs;
            });
        }
    }

    const route = meta();

    onMount( () => {
    })

    function onAdd() {
        console.log( 'Add' );
        const breeder = {
            id:0, name:'', clubName:'',
            details: {
                id:null, name:null, email:null, districtId:districtId, clubId:null, start:null, end:null, info:null,
            },
            edit:true
        };
        breeders.splice( 0, 0, breeder ); // insert as first
        breeders = breeders; // redraw
    }

    function onEdit( breeder ) { // should be getting breeder.details from api!
        return ( event ) => {
            if( breeder.id ) {
                api.breeder.get(breeder.id).then(response => {
                    breeder.details = response.breeder;
                    breeders = breeders;
                });
                breeder.edit = breeder.edit ? false : true;
            }
        }
    }

    function onChange( breeder ) {
        return (event ) => {
            console.log( 'Changed', breeder );
            breeder.changed = true;
            breeders = breeders; // redraw
        }
    }

    function onSubmit( breeder ) {
        return (event ) => {
            if( breeder.changed ) {
                breeder.changed = false;
                api.breeder.post(breeder.details).then( response => {
                    console.log( 'Submit', breeder, response );
                    breeder.id = response.id;
                    breeder.name = breeder.details.name
                    breeder.clubName = breeder.details.clubName;
                    breeder.start = breeder.details.start;
                    breeder.end = breeder.details.end;
                    breeder.edit = false;
                    breeder.details = null;
                    breeders = breeders;
                });
            }

        }
    }

    $: handle( districtId );

</script>

{#if $user}
    <h32 class='text-center'>Obmann {#if $user} {$user.name} {/if} → Verband {#if district} {district.name} {/if} → Züchter</h32>
    {#if breeders}
        <div class='flex flex-row border border-gray-400 rounded-t px-8 py-2 bg-header gap-x-1 font-bold'>
            <div class='w-8'>Id</div>
            <div class='w-64'>Name</div>
            <div class='w-32'>Ortsverein</div>
            <div class='w-32'>Aktiv</div>
            <div class='grow'></div>
            <div class='w-18'>Incl Abgemeldete</div> <input type='checkbox' bind:checked={allBreeders}>
            <div class='w-8'></div>
            <div class='w-8 cursor-pointer' on:click={onAdd} title='Neues Mitglied'>+</div>
        </div>

        <div class='grow bg-gray-100 overflow-y-scroll border border-t-0 border-gray-600 rounded-b px-8 scrollbar'>
            {#if breeders}
                {#each breeders as breeder}
                    {#if allBreeders || ! breeder.end }
                        <div class='flex'>
                            <div class='flex flex-row gap-x-1'>
                                <div class='w-8 border'>{breeder.id}</div>
                                <a class='w-64 border' href={route.match+'/'+breeder.id}>{txt( breeder.name )}</a>
                                <div class='w-32 border'>{txt( breeder.clubName )}</div>
                                {#if !breeder.end}
                                    <div class='w-32 border text-green-700'>&#10003;</div>
                                {:else}
                                    <div class='w-32 border text-red-700'>&#10005;</div>
                                {/if}
                            </div>
                            <div class='grow'></div>
                            {#if breeder.edit}
                                <span class='cursor-pointer text-red-600' on:click={onEdit(breeder)} title='schließen'>[ &#9998; ]</span>
                            {:else}
                                <span class='cursor-pointer text-green-600' on:click={onEdit(breeder)} title='bearbeiten'>[ &#9998; ]</span>
                            {/if}
                        </div>

                        {#if breeder.edit && breeder.details }
                            <form class='flex flex-col border border-gray-400 rounded m-4 p-2' on:input={onChange(breeder)}>

                                    <Text class='w-64' bind:value={breeder.details.name} label='Name' required/>

                                    <Text class='w-128' bind:value={breeder.details.email} label='Email Adresse'/>

                                    <Select class='w-64' label='Ortsverein' bind:value={breeder.details.clubId} required>
                                        {#if clubs}
                                            {#each clubs as club}
                                                <option class='bg-white' value={club.id}> {club.name} </option>
                                            {/each}
                                        {/if}
                                    </Select>
                                    <div class='flex gap-2'>
                                        <Date class='w-24' label='Mitglied seit' bind:value={breeder.details.start} required/>
                                        <Date class='w-24' label='Mitglied bis' bind:value={breeder.details.end} />
                                    </div>
                                    <Text class='w-128' label='Info' bind:value={breeder.details.info} />

                                    {#if breeder.changed}
                                        <Button class='edit' on:click={onSubmit(breeder)} label='' value='speichern' />
                                    {/if}
                            </form>
                        {/if}
                    {/if}
                {/each}
            {/if}
        </div>


    {/if}
{:else}
    NOT AUTORIZED
{/if}



<style>

</style>
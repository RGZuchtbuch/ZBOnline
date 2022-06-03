<script>
    import { onMount } from 'svelte';
    import { active, meta, router, Route } from 'tinro';
    import api from '../scripts/api.js';
    import Box from './Box.svelte';
    import IconButton from '@smui/icon-button';
    import TextField from '@smui/textfield';
    import Select, { Option } from '@smui/select';
    import {user} from "../scripts/store";

    const route = meta();


    export let promise;
    let district = null;
    let disabled = true; // not edit

    console.log( 'District' );

    function handlePromise( promise ) {
        promise
            .then( data => {
                console.log( 'Data', data );
                district = data.district;
                disabled = district.id !== 0;
            } )
            .catch( error => {
                district = null;
            } );
    }

    function edit() {
        disabled = false;
    }

    function save() {
        disabled = true;
        let promise = null
        if( district.id === 0 ) {
            promise = api.postDistrict( district );
        } else {
            promise = api.putDistrict( district );
        }
        promise.then( response => {
            //district.id = response.id;
            console.log( 'district saved');
        }).catch( response => {
            disabled = false;
            console.log( 'district not saved', response.status );
        });
    }

    function toParent() {
        console.log( 'to parent ', district.parent );

    }

    let currentUser = null;
    user.subscribe( value => {
        currentUser = value;
        console.log( currentUser );
    });

    $: handlePromise( promise );

</script>

{#if district }
    <Box legend='District {district.name}'>
        <form>
            <div class='flex flex-row justify-between'>
                <div>
                    {#if district.parent}
                        <a href={'/#/district/'+district.parent}>
                            <IconButton class='material-icons self-end' on:click={toParent} title='Zurück'>trending_up</IconButton>
                        </a>
                    {/if}
                </div>
                <div>
                    {#if disabled}
                        <IconButton class='material-icons self-end' on:click={edit} title='Aendern'>edit</IconButton>
                    {:else}
                        <IconButton class='material-icons self-end' on:click={save} title='Speichern'>done</IconButton>
                    {/if}
                </div>
            </div>


            <div class='flex flex-row'>
                <TextField bind:value={district.name} label='Name' {disabled} style='width:24em'> </TextField>
                <TextField bind:value={district.short} label='Abk.' {disabled} style='width:8em'> </TextField>
            </div>
            <div class='flex flex-row'>
                <TextField bind:value={district.fullname} label='Name komplett' {disabled} style='width:24em'> </TextField>
            </div>
            <div class='flex flex-row'>
                <TextField bind:value={district.coordinates} label='Coordinates (Lat, Lon)' {disabled} style='width:32em'> </TextField>
            </div>
        </form>

        <Box legend='Verbände'>
            <div class='flex flex-row'>
                <div class='grow flex flex-col'>
                    {#each district.children as district}
                        <div class='nowrap'>→ <a href={'/district/'+district.id}>{district.name} ({district.short})</a></div>
                    {/each}
                </div>
                {#if currentUser && currentUser.isAdmin && ! disabled }
                    <div><a href={'/#/district/'+district.id+'/new'}>+</a></div>
                {/if}
            </div>
        </Box>

    </Box>
{:else}
    Oeps for district
{/if}

<style>
</style>
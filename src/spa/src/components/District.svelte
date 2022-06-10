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
            promise = api.district.post( district );
        } else {
            promise = api.district.put( district );
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

    function deleteDistrict( district ) {
        console.log('prep del');
        return (event) => {
            console.log( 'Delete ', district.id );
            api.district.delete( district.id )
                .then( response => {
                    router.goto( '/district/'+district.parent);
                })
                .catch( error => {
                    console.log( 'Error deleting district');
                });
        }
    }

    function deleteModerator( moderator ) {
        console.log('prep del', moderator);
        return (event) => {
            console.log( 'Delete ', district.id, moderator.id );
            api.moderator.delete( district.id, moderator.id )
                .then( response => {
                    router.goto( '/district/'+district.id);
                })
                .catch( error => {
                    console.log( 'Error deleting moderator');
                });
        }
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
                        Im
                        <a href={'/#/district/'+district.parent.id}> {district.parent.name } </a>
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

        <Box legend='Obmänner'>
            <div class='flex flex-col'>
                <div class='flex flex-row justify-between bar text-s' >
                    <div>&nbsp;</div>
                    {#if currentUser && currentUser.isAdmin && ! disabled }
                        <a href={'/#/district/'+district.id+'/moderator/new'}>
                            <img class='w-4 h-4' src='assets/add.png' alt='Neu'>
                        </a>
                    {/if}
                </div>


                {#each district.moderators as moderator}
                    <div class='flex flex-row justify-between'>
                        <div class='nowrap'>→ {moderator.name}</div>
                        {#if currentUser && currentUser.isAdmin && ! disabled }
                            <div class='cursor-pointer' on:click={deleteModerator( moderator )}>
                                <img class='w-4 h-4' src='assets/delete.png' alt='Entfernen'>
                            </div>
                        {/if}
                    </div>
                {/each}
            </div>
        </Box>

        <Box legend='Verbände/Vereine'>
            <div class='flex flex-col'>

                <div class='flex flex-row justify-between bar text-m' >
                    <div>&nbsp;</div>
                    {#if currentUser && currentUser.isAdmin && ! disabled }
                        <a href={'/#/district/'+district.id+'/new'}>
                            <img class='w-4 h-4' src='assets/add.png' alt='Neu'>
                        </a>
                    {/if}
                </div>

                {#each district.children as district}
                    <div class='grow flex flex-rol justify-between'>
                        <div class='nowrap'>→ <a href={'/district/'+district.id}>{district.name} ({district.short})</a></div>
                        {#if currentUser && currentUser.isAdmin && ! disabled }
                            <div class='cursor-pointer' on:click={deleteDistrict( district )}>
                                <img class='w-4 h-4' src='assets/delete.png' alt='Entfernen'>
                            </div>
                        {/if}
                    </div>
                {/each}
            </div>
        </Box>

    </Box>
{:else}
    Oeps for district
{/if}

<style>
    .bar {
        margin-top: -.5em;
    }
</style>
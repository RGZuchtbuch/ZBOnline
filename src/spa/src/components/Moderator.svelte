<script>
    import { onMount } from 'svelte';
    import { active, meta, router, Route } from 'tinro';
    import api from '../scripts/api.js';
    import Box from './Box.svelte';
    import FormField from '@smui/form-field';
    import Radio from '@smui/radio';
    import IconButton from '@smui/icon-button';
    import TextField from '@smui/textfield';
    import Select, { Option } from '@smui/select';
    import {user} from "../scripts/store";

    const route = meta();


    export let promise;

    let moderator = null;
    let selected;

    let disabled = false; // not edit

    let canSave = false;
    $: canSave = selected;

    console.log( 'Moderator' );

    promise.then( data => {
        console.log( 'Got Moderator', data.moderator )
        moderator = data.moderator;
    })

    function edit() {
        disabled = false;
    }

    function save() {
        console.log( 'Selected', moderator, selected );
        disabled = true;

        if( moderator.id === 0 ) {
            let data = {id:selected.id, district:moderator.district.id}
            console.log('save new', data );
            api.moderator.post( moderator.district.id, selected.id )
                .then(response => {
                    console.log('moderator saved');
                    history.back();
                })
                .catch(response => {
                    disabled = false;
                    console.log('moderator not saved', response.status);
                });
        }
    }

    let currentUser = null;
    user.subscribe( value => {
        currentUser = value;
        console.log( currentUser );
    });

</script>


{#await promise}
    loading...
{:then data}
    <Box legend='Obmann {data.moderator.district.name}'>
        <form>
            <div class='flex flex-row justify-between'>
                <div></div>

                <div>
                    {#if canSave}
                        <IconButton class='material-icons self-end' on:click={save} title='Speichern'>done</IconButton>
                    {/if}
                </div>
            </div>

            <div class='flex flex-col'>
                {#each data.moderator.users as user}
                    <FormField>
                        <Radio bind:group={selected} value={user} disabled={disabled}/>{user.name}
                    </FormField>
                {/each}
            </div>
        </form>

    </Box>
{:catch error}
    Oeps
{/await}


<style>
</style>
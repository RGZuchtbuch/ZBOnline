<script>
    //    import {Router} from 'tinro'; // Router store
    import { createEventDispatcher } from 'svelte';
    import {meta} from "tinro";
    import api from '../../js/api.js';
    import {user} from '../../js/store.js';
    import {txt} from '../../js/util.js';
    import Button from '../common/input/Button.svelte';
    import NumberInput from '../common/input/Number.svelte';
    import Select from '../common/input/Select.svelte';
    import TextInput from '../common/input/Text.svelte';
    import TextArea from '../common/input/TextArea.svelte';

    export let article = null;
    export let show = false;
    let edit = false;

    let details = null;
    let changed = false;
    let children = null;
    let breeders = null;

    const route    = meta();
    const dispatch = createEventDispatcher();

    let open = false;
    function onToggleOpen() {
        open = ! open;
    }

    function onToggleShow() {
        show = ! show;
        if( show ) {
            // load district details
            if( article.id ) { // existing
                api.article.get( article.id ).then( response => {
                    details = response.article;
                });
            } else { // new, default values
                details = { id:null, title:null, html:null };
            }
        }
    }

    function onToggleEdit() {
        console.log( 'edit' );
        edit = ! edit;
    }

    function onChange( event ) {
        changed = true;
    }

    function onSubmit() {
        console.log('Submit article', article);
        if (article.id) {
            api.article.update( details.id, details ).then(response => {
                article.title = details.title;
            });

        } else {
            api.article.create( details ).then(response => {
                details.id = response.id;
                article.id = response.id;
                article.title = details.title;
            });
        }
        edit = false;
        changed = false;
    }
</script>

<div class='flex flex-col px-2'>
    <div class='flex flex-row'>
        <div class='w-12 whitespace-nowrap'>({article.id}) →</div>
        <div class='grow cursor-pointer' on:click={onToggleShow}>{article.title}</div>
    </div>

    {#if show && details }
        <form class='flex flex-col border border-gray-400 rounded m-4' on:input={onChange}>
            {#if details }
                <div class='flex bg-header px-4'>
                    <TextInput class='grow font-bold text-2xl' bind:value={details.title} label='Titel' required disabled={!edit}/>
                    <div class='w-8' on:click={onToggleEdit}>E</div>
                </div>
                {#if edit}
                    <TextArea class='p-2' bind:value={details.html}></TextArea>
                {/if}
                <div class='p-4'>{@html details.html}</div>

                {#if edit && changed}
                    <Button class='edit' on:click={onSubmit} label='' value='speichern' />
                {/if}
            {/if}
        </form>
    {/if}

</div>

<style>
    .edit {
        @apply cursor-pointer;
    }
    select {
        background: green;
    }
</style>
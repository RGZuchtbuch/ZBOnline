<script>
    import api from '../../../js/api.js';
    import Form from '../../common/form/Form.svelte';
    import TextInput from '../../common/form/input/TextInput.svelte';
    import TextArea from '../../common/form/input/TextArea.svelte';
    import Status from '../../common/form/Status.svelte';

    export let article = null;
    export let show = false;
    let edit = false;

    let details = null;

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

    function onSubmit() {
        console.log('Submit article', article);
        if (article.id) {
            api.article.put( details.id, details ).then(response => {
                article.title = details.title;
            });

        } else {
            api.article.post( details ).then(response => {
                details.id = response.id;
                article.id = response.id;
                article.title = details.title;
            });
        }
    }

</script>

<div class='flex flex-col px-2'>
    <div class='flex flex-row'>
        <div class='w-12 whitespace-nowrap'>({article.id}) →</div>
        <button type='button' class='grow cursor-pointer text-left' on:click={onToggleShow}>{article.title}</button>
    </div>

    {#if show && details }
        <Form class='flex flex-col border border-gray-400 rounded m-4' on:submit={onSubmit}>
            {#if details }
                <div class='text-right' on:click={onToggleEdit}>[ E ]</div>
                <div class='flex px-4'>
                    <TextInput class='grow font-bold text-2xl' bind:value={details.title} label='Titel' required disabled={!edit}/>
                    <Status />
                </div>
                {#if edit}
                    <TextArea class='h-64 p-2' bind:value={details.html}></TextArea>
                {/if}
                <div class='p-4'>{@html details.html}</div>
            {/if}
        </Form>
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
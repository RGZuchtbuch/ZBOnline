<script>
    import {meta, router} from 'tinro';
    import api from '../../js/api.js';
    import {txt} from '../../js/util.js';
    import { user } from '../../js/store.js'
    import EmailInput from './input/Email.svelte';
    import Modal from './Modal.svelte';
    import Submit from './input/Submit.svelte';
    import TextInput from './input/Text.svelte';
    import TextArea from './input/TextArea.svelte';

    export let districtId = null;
    let district = null;
    let moderator = null;
    let from = '';
    let name = '';
    let subject = '';
    let message = ''
    let confirm = false;
    let success = true;
    let invalids = { from:false, name:false, subject:false, message:false };
    let invalid = true;
    let sending = false;

    let route = meta();

    function onInput() { // verify invalid
        invalid = district === null;
        for( let key in invalids ) {
            invalid = invalid || invalids[ key ];
        }
    }

    function onSubmit() {
        if( ! invalid ) {
            sending = true;
            api.message.post( districtId, from, name, subject, message, confirm ).then(response => { // note confirm is antibot
                success = response.success;
                if (success) {
                    router.goto(route.from);
                    sending = false;
                }
            });
        }
    }

    function loadDistrict( districtId ) {
        api.district.get( districtId ).then( response => {
            district = response.district;
        })
    }

    function cancel() {
        router.goto( route.from );
    }

    $: loadDistrict( districtId );

</script>

<Modal class=''>
    <form class='w-160 flex flex-col self-center rounded' on:input={onInput} on:submit|preventDefault={onSubmit}>
        <div class='flex bg-header rounded-t p-4'>
            <h2 class='grow text-white'>Nachricht am Obmann vom {#if district}{district.name}{/if}</h2>
            <div class='w-8 justify-center m-2 circled bg-alert cursor-pointer' on:click={cancel}>X</div>
        </div>
        <div class='flex flex-col gap-4 rounded-b bg-gray-200 gap-4 p-4'>
            <div>An {#if district}{district.moderator.firstname} {txt(district.moderator.infix)} {district.moderator.lastname}{/if}</div>
            <EmailInput class='' label='eMail *' bind:value={from} bind:invalid={invalids.from} required />
            <TextInput label='Ihr Name *' error='Unvollständiger Name' bind:value={name} bind:invalid={invalids.name} required />
            <TextInput label='Betrifft *' error='Unvollständig' bind:value={subject} bind:invalid={invalids.subject} required />
            <TextArea label='Nachricht *' error='Braucht eine Nachricht' bind:value={message} bind:invalid={invalids.message} required/>
            <input type='checkbox' name='confirm' bind:checked={confirm} >

            {#if ! invalid && ! sending }
                <Submit value='Verschicken am Obmann &#10170;' disabled={invalid} />
            {/if}
        </div>
    </form>
</Modal>

<style>

    h2 {
        @apply rounded;
    }
    input[type='checkbox'] {
        @apply hidden;
    }

</style>
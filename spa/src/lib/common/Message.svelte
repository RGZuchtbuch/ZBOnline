<script>
    import {meta, router} from 'tinro';
    import api from '../../js/api.js';
    import validator from '../../js/validator.js';

    import Modal from './Modal.svelte';
    import EmailInput from './form/input/EmailInput.svelte';
    import TextInput from './form/input/TextInput.svelte';
    import TextArea from './form/input/TextArea.svelte';
    import Submit from './form/Submit.svelte';
    import Form from './form/Form.svelte';

    export let districtId = null;
    let district = null;
    let moderator = null;
    let from = '';
    let name = '';
    let subject = '';
    let message = ''
    let confirm = false;
    let success = true;
    let invalid = true;

    let route = meta();

    const validate = {
        email:      (v) => validator(v).email().isValid(),
        name:       (v) => validator(v).string().length( 2, 255 ).isValid(),
        subject:    (v) => validator(v).string().length( 2, 255 ).isValid(),
        message:    (v) => validator(v).string().length( 2, 65535 ).isValid(),
    }

    function onSubmit() {
        api.message.post( districtId, from, name, subject, message, confirm ).then(response => { // note confirm is antibot
            success = response.success;
            console.log( 'Response', response );
            if (success) {
                router.goto(route.from);
            }
        });
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
    <Form class='w-160 flex flex-col self-center rounded' autoSave={false} on:submit={onSubmit}>
        <div class='flex bg-header rounded-t p-4'>
            <h2 class='grow text-white'>Nachricht am Obmann vom {#if district}{district.name}{/if}</h2>
            <div class='w-8 justify-center m-2 circled bg-alert cursor-pointer' on:click={cancel}>X</div>
        </div>
        <div class='flex flex-col gap-4 rounded-b bg-gray-200 gap-4 p-4'>
            <div>Am Obmann</div>
            <EmailInput class='' label='eMail *' bind:value={from} validator={validate.email}/>
            <TextInput label='Ihr Name *' error='Unvollständiger Name' bind:value={name} validator={validate.name}  />
            <TextInput label='Betrifft *' error='Unvollständig' bind:value={subject} validator={validate.subject}  />
            <TextArea label='Nachricht *' error='Braucht eine Nachricht' bind:value={message} validator={validate.message} />
            <input type='checkbox' name='confirm' bind:checked={confirm} >

            <Submit noChangeValue='?'
                submitValue='Verschicken am Obmann &#10170;'
                invalidValue='X'
            />
        </div>
    </Form>
</Modal>

<style>

    h2 {
        @apply rounded;
    }
    input[type='checkbox'] {
        @apply hidden;
    }

</style>
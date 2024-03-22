<script>
    import {meta, router} from 'tinro';
    import api from "../../js/api.js";
    import { user } from '../../js/store.js'
    import Button from '../common/input/Button.svelte';
    import Modal from '../common/Modal.svelte';
    import Form from '../common/form/Form.svelte';
    import EmailInput from '../common/form/input/EmailInput.svelte';
    import PasswordInput from '../common/form/input/PasswordInput.svelte';
    import Submit from '../common/form/Submit.svelte';
    import validator from '../../js/validator.js';

    let email = $router.query.email;
    let token = $router.query.token;
    let password = null;
    let success = true;
    let invalids = { email:false, password:false };
    let invalid = true;

    let route = meta();

    const validate = {
        //email:     (v) => validator(v).email().isValid(), // 1-10 → 30-09
        password:  (v) => validator(v).password().isValid(), // pigeons
    }

    function onSubmit() {
        api.user.password( email, token, password ).then( response => {
            success = response.success;
            if( response.success ) {
                router.goto( '/' );
            }
        });
    }

    function cancel() {
        router.goto( route.from );
    }

</script>

<Modal class=''>
    <Form class='w-96 flex flex-col gap-4 self-center border rounded p-4' on:submit={onSubmit}>
        <div class='flex bg-header'>
            <h2 class='grow '>Paswort ändern</h2>
            <div class='cursor-pointer mr-2' on:click={cancel}>&#8855;</div>
        </div>
        <EmailInput class='' label='eMail' bind:value={email}/>
        <div class='italic text-xs'>Das Passwort braucht minimal 8 Zeichen, kleine und große Buchstaben [a-z][A-Z], eine Nummer [0-9] und ein Sonderzeichen [!@#$%^&*(),.:;]</div>
        <PasswordInput class='' label='Password' bind:value={password} validator={validate.password}/>
        <Submit submitValue='Passwort zu einfach !'/>
        {#if ! success} <div class='error'>Nicht erfolgreich</div> {/if}
    </Form>

</Modal>

<style>
    .error {
        @apply text-red-600;
    }
    h2 {
        @apply rounded;
    }

</style>
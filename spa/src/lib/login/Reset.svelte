<script>
    import {meta, router} from 'tinro';
    import api from "../../js/api.js";
    import { user } from '../../js/store.js'
    import Button from '../common/input/Button.svelte';
    import EmailInput from '../common/input/Email.svelte';
    import Modal from '../common/Modal.svelte';
    import PasswordInput from '../common/input/Password.svelte';
    import Submit from '../common/input/Submit.svelte';

    let email = $router.query.email;
    let token = $router.query.token;
    let password = null;
    let success = true;
    let invalids = { email:false, password:false };
    let invalid = true;

    let route = meta();

    function checkInvalid() {
        invalid = false;
        for( let key in invalids ) {
            invalid |= invalids[ key ];
        }
        invalid |= ! checkPassword( password );
    }

    function checkPassword( password ) {
        // min 8 chars, having a-z, A-Z, 0-9 and one that is not a-z,A-Z, 0-9 : should match backends check !
        let regex = /(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})/;
        return regex.test( password );
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

    $: checkInvalid( invalids, password );

</script>

<Modal class=''>
    <form class='w-96 flex flex-col gap-4 self-center border rounded p-4' on:submit|preventDefault={onSubmit}>
        <div class='flex bg-header'>
            <h2 class='grow '>Paswort ändern</h2>
            <div class='cursor-pointer mr-2' on:click={cancel}>&#8855;</div>
        </div>
        <EmailInput class='' label='eMail' bind:value={email} bind:invalid={invalids.email} disabled />
        <div class='italic text-xs'>Das Passwort braucht minimal 8 Zeichen, kleine und große Buchstaben [a-z][A-Z], eine Nummer [0-9] und ein Sonderzeichen [!@#$%^&*(),.:;]</div>
        <PasswordInput class='' label='Password' bind:value={password} bind:invalid={invalids.password} required/>

        {#if invalid}
            <Submit value='Passwort zu einfach !' disabled />
        {:else}
            <Submit value='Ich wil herein' />
        {/if}
        {#if ! success} <div class='error'>Nicht erfolgreich</div> {/if}
    </form>

</Modal>

<style>
    .error {
        @apply text-red-600;
    }
    h2 {
        @apply rounded;
    }

</style>
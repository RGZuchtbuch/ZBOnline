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
            invalid = invalid || invalids[ key ];
        }
    }

    function onSubmit() {
        api.user.password( email, token, password ).then( response => {
            success = response.success;
            if( response.success ) {
                console.log( "Set pasword succesfully, wolcome" )
                router.goto( '/' );
            }
        });
    }

    function cancel() {
        router.goto( route.from );
    }

    $: checkInvalid( invalids );

    console.log( email, token );

</script>

<Modal class=''>
    <form class='w-96 flex flex-col gap-4 self-center border rounded p-4' on:submit|preventDefault={onSubmit}>
        <div class='flex bg-header'>
            <h2 class='grow '>Paswort ändern</h2>
            <div class='cursor-pointer mr-2' on:click={cancel}>&#8855;</div>
        </div>
            <EmailInput class='' label='eMail' bind:value={email} bind:invalid={invalids.email} disabled/>
            <PasswordInput class='' label='Password' bind:value={password} bind:invalid={invalids.password} required/>

            {#if ! invalids.email && ! invalids.password }
                <Submit value='Ich wil herein' disabled={invalid} />
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
<script>
    import {meta, router} from 'tinro';
    import api from "../js/api.js";
    import { user } from '../js/store.js'
    import Button from './common/input/Button.svelte';
    import EmailInput from './common/input/Email.svelte';
    import Modal from './Modal.svelte';
    import PasswordInput from './common/input/Password.svelte';
    import Submit from './common/input/Submit.svelte';

    let email = 'eelco.jannink@gmail.com';
    let password = '';
    let success = true;
    let invalids = { email:false, password:false };
    let invalid = true;
    let forgot = false;

    let route = meta();

    function checkInvalid() {
        invalid = false;
        for( let key in invalids ) {
            invalid = invalid || invalids[ key ];
        }
    }

    function onSubmit() {
        api.user.login( email, password ).then(response => {
            success = response.success;
            if( success ) {
                console.log( $user )
                router.goto( route.from );
            }
        });
    }

    function onForgot( event ) {
        forgot = ! forgot;
        console.log( 'Forgot', forgot );
    }

    function onResetPassword( event ) {
        console.log( event.target.disabled );
        if( event.target.disabled )
            console.log( 'Disabled' )
        else
            console.log( 'Enabled' );
        event.target.disabled = true;
        console.log( "Reset password requested");
        api.user.reset( email ).then( response => {
            if( response.success ) {
                console.log( "Reset succesfull" )
                event.target.disabled = false;
                router.goto( route.from );
            }
        });

    }

    function cancel() {
        router.goto( route.from );
    }

    $: checkInvalid( invalids );

</script>

<Modal class=''>
    <form class='w-96 flex flex-col gap-4 self-center border rounded p-4' on:submit|preventDefault={onSubmit}>
        <div class='flex bg-header'>
            {#if forgot}
                <h2 class='grow '>Passwort Reset</h2>
            {:else}
                <h2 class='grow '>Anmelden</h2>
            {/if}
            <div class='cursor-pointer mr-2' on:click={cancel}>&#8855;</div>
        </div>
        <EmailInput class='' label='eMail' bind:value={email} bind:invalid={invalids.email} required/>

        {#if ! forgot}
            <PasswordInput class='' label='Passwort' bind:value={password} bind:invalid={invalids.password} required/>
        {/if}

        {#if forgot }
            <Button value='&#9993; Schick mir eine reset Mail!' on:click={onResetPassword} alert=true/>
        {/if}

        {#if ! forgot && ! invalids.email && ! invalids.password }
            <Submit value='Las mich herein &#10170;' disabled={invalid} />
            <div class='cursor-pointer error text-sm text-right' on:click={onForgot}>Ich habe mein Passwort vergessen :(</div>
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
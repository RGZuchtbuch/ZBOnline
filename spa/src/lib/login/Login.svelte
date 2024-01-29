<script>
    import {meta, router} from 'tinro';
    import api from "../../js/api.js";
    import { user } from '../../js/store.js'
    import Button from '../common/input/Button.svelte';
    import EmailInput from '../common/input/Email.svelte';
    import Modal from '../common/Modal.svelte';
    import PasswordInput from '../common/input/Password.svelte';
    import Submit from '../common/input/Submit.svelte';

    let email = 'email';
    let password = '';
    let success = true;
    let invalids = { email:false, password:false };
    let invalid = true;
    let forgot = false;

    let route = meta();

    function onInput() { // verify invalid
        invalid = false;
        for( let key in invalids ) {
            invalid = invalid || invalids[ key ];
        }
    }

    function onSubmit() {
        if( ! invalid ) {
            api.user.login(email, password).then(response => {
                success = response.success;
                if (success) {
                    router.goto(route.from);
                }
            });
        }
    }

    function onForgot( event ) {
        forgot = ! forgot;
    }

    function onResetPassword( event ) {
        event.target.disabled = true;
        api.user.reset( email ).then( response => {
            if( response.success ) {
                event.target.disabled = false;
                router.goto( route.from );
            }
        });

    }

    function cancel() {
        router.goto( route.from );
    }

</script>

<Modal class=''>
    <form class='w-96 flex flex-col self-center rounded' on:input={onInput} on:submit|preventDefault={onSubmit}>
        <div class='flex bg-header rounded-t p-4'>
            {#if forgot}
                <h2 class='grow text-white'>Passwort Reset</h2>
            {:else}
                <h2 class='grow text-white'>Anmelden</h2>
            {/if}
            <div class='w-8 justify-center m-2 circled bg-alert cursor-pointer' on:click={cancel}>X</div>
        </div>

        <div class='flex flex-col gap-4 rounded-b bg-gray-200 gap-4 p-4'>
            <EmailInput label='eMail *' bind:value={email} bind:invalid={invalids.email} required/>

            {#if ! forgot}
                <PasswordInput label='Passwort *' bind:value={password} bind:invalid={invalids.password} required/>
            {/if}

            {#if forgot }
                <Button value='&#9993; Schick mir eine reset Mail!' on:click={onResetPassword} alert=true />
            {/if}

            {#if ! forgot && ! invalids.email && ! invalids.password }
                <Submit value='Las mich herein &#10170;' disabled={invalid} />
                <div class='cursor-pointer error text-sm text-right' on:click={onForgot}>Ich habe mein Passwort vergessen :(</div>
            {/if}
            {#if ! success} <div class='error'>Nicht erfolgreich</div> {/if}
        </div>
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
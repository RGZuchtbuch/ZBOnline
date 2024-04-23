<script>
    import { getContext,  onDestroy, onMount } from 'svelte';
    import {meta, router} from 'tinro';
    import api from "../../js/api.js";
    import { user } from '../../js/store.js'

    import Modal from '../common/Modal.svelte';
    import Form from '../common/form/Form.svelte';
    import TextInput from '../common/form/input/TextInput.svelte';
    import PasswordInput from '../common/form/input/PasswordInput.svelte';
    import Submit from '../common/form/Submit.svelte';
    import validator from '../../js/validator.js';

    let email = 'email';
    let password = '';
    let success = true;

    let forgot = false;

    const state = getContext( 'form'); // store

    let route = meta();

    const validate = {
        email:     (v) => validator(v).email().isValid(),
        password:  (v) => validator(v).password().isValid(),
    }

    function onSubmit() {
        if( forgot ) {
            console.log('Login forgot');
            api.user.reset( email ).then( response => {
                if( response.success ) {
                    router.goto( route.from );
                }
            });
        } else {
            console.log('Login submit');
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
        email = null;
    }

    /*
    function onResetPassword( event ) {
        event.target.disabled = true;
        api.user.reset( email ).then( response => {
            if( response.success ) {
                event.target.disabled = false;
                router.goto( route.from );
            }
        });

    }
    */

    function cancel() {
        router.goto( route.from );
    }

    console.log( 'State', $state );

</script>

<Modal class=''>

    <Form class='w-96 flex flex-col self-center rounded pt-16' autoSave={false} on:submit={onSubmit}>
        <div class='flex bg-header rounded-t p-4'>
            {#if forgot}
                <h2 class='grow text-white'>Passwort Reset</h2>
            {:else}
                <h2 class='grow text-white'>Anmelden neu</h2>
            {/if}
            <button type='button' class='w-8 justify-center m-2 circled bg-alert cursor-pointer' on:click={cancel}>X</button>
        </div>
        <div class='flex flex-col gap-4 rounded-b bg-gray-200  p-4'>
            <TextInput label='eMail *' bind:value={email} validator={validate.email}/>

            {#if ! forgot}
                <PasswordInput label='Passwort *' bind:value={password}/>
            {/if}

            {#if forgot }
                <Submit submitValue='&#9993; Schick mir eine reset Mail!' invalidValue='Falsche Adresse' />
            {:else}
                <Submit submitValue='Las mich herein &#10170;' invalidValue='Falsche eingabe(n)'/>
                <button type='button' class='cursor-pointer error text-sm text-right' on:click={onForgot}>Ich habe mein Passwort vergessen :(</button>
            {/if}
            {#if ! success} <div class='error'>Nicht erfolgreich</div> {/if}
        </div>
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
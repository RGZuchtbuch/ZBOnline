<script>
    import {meta, router} from 'tinro';
    import api from "../js/api.js";
    import { user } from '../js/store.js'
    import Submit from './input/Submit.svelte';
    import EmailInput from './input/Email.svelte';
    import Modal from './Modal.svelte';
    import PasswordInput from './input/Password.svelte';

    let email = 'eelco.jannink@gmail.com';
    let password = 'jannink';
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
        api.user.login( email, password ).then(response => {
            success = response.success;
            if( success ) {
                console.log( $user )
                router.goto( route.from );
            }
        });
    }

    $: checkInvalid( invalids );

</script>

<Modal class=''>
    <form class='w-96 flex flex-col gap-4 self-center border rounded p-4' on:submit|preventDefault={onSubmit}>
        <h2 class='bg-header'>Anmelden</h2>
        <EmailInput class='' label='eMail' bind:value={email} bind:invalid={invalids.email} required/>
        <PasswordInput class='' label='Password' bind:value={password} bind:invalid={invalids.password} required/>
        <Submit value='Ok' disabled={invalid} />
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
    .invalid {
        @apply bg-red-300;
    }
</style>
<script>
    import {meta, router} from 'tinro';
    import api from "../js/api.js";
    import { user } from '../js/store.js'
    import EmailInput from './input/Email.svelte';
    import PasswordInput from './input/Password.svelte';

    let email = 'eelco.jannink@gmail.com';
    let password = 'jannink';
    let success = true;

    let route = meta();

    function onSubmit() {
        api.user.login( email, password ).then(response => {
            success = response.success;
            if( success ) {
                console.log( $user )
                router.goto( route.from );
            }
        });
    }

</script>

<form class='flex flex-col self-center' on:submit|preventDefault={onSubmit}>
    <h2>Anmelden</h2>
    <EmailInput bind:value={email} />
    <PasswordInput bind:value={password} />
    <button type="submit">Submit</button>
    {#if ! success}
        <div class='error'>Nicht erfolgreich</div>
    {/if}
</form>

<style>
    .error {
        @apply text-red-600;
    }
</style>
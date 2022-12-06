<script>
    import {meta, router} from 'tinro';
    import api from "../js/api.js";
    import { user } from '../js/store.js'
    import EmailInput from './input/Email.svelte';
    import PasswordInput from './input/Password.svelte';

    let email = 'eelco.jannink@gmail.com';
    let password = 'jannink';

    let route = meta();

    function onSubmit() {
        api.user.credentials( email, password ).then( response => {
            console.log( $user )
            router.goto( route.from );
        });
    }

</script>

<form on:submit|preventDefault={onSubmit}>
    <h2>Anmelden</h2>
    <EmailInput bind:value={email} />
    <PasswordInput bind:value={password} />
    <button type="submit">Submit</button>
</form>

<style>

</style>
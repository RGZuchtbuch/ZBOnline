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
        user.set( null );
        router.goto( '/' );
    }

</script>

<form on:submit|preventDefault={onSubmit}>
    <h2>Abmelden</h2>
    <button type="submit">Submit</button>
</form>

<style>

</style>
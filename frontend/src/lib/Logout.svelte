<script>
    import {meta, router} from 'tinro';
    import api from "../js/api.js";
    import { user } from '../js/store.js'
    import Submit from './input/Submit.svelte';
    import Modal from './Modal.svelte';

    let email = 'eelco.jannink@gmail.com';
    let password = 'jannink';

    let route = meta();

    function onSubmit() {
        api.user.logout();
        router.goto( '/' );
    }

    function cancel() {
        router.goto( route.from );
    }
</script>

<Modal class=''>
    <form class='w-64 flex flex-col gap-4 self-center border p-4' on:submit|preventDefault={onSubmit}>
        <div class='flex bg-header rounded'>
            <h2 class='grow'>Abmelden</h2>
            <div class='cursor-pointer mr-2' on:click={cancel}>&#8855;</div>
        </div>
        <div>{ $user.fullname }</div>
        <Submit value='Ok' />
    </form>
</Modal>
<style>

</style>
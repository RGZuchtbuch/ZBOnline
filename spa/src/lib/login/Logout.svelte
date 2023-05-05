<script>
    import {meta, router} from 'tinro';
    import api from "../../js/api.js";
    import { user } from '../../js/store.js'
    import Submit from '../common/input/Submit.svelte';
    import Modal from '../common/Modal.svelte';

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
    <form class='w-96 flex flex-col self-center rounded' on:submit|preventDefault={onSubmit}>
        <div class='flex bg-header rounded-t p-4'>
            <h2 class='grow text-white'>Abmelden</h2>
            <div class='w-8 justify-center m-2 circled bg-alert cursor-pointer' on:click={cancel}>X</div>
        </div>
        <div class='flex flex-col gap-4 rounded-b bg-gray-200 gap-4 p-4'>
            <div>{ $user.email }</div>
            <Submit value='Ok' />
        </div>
    </form>
</Modal>
<style>

</style>
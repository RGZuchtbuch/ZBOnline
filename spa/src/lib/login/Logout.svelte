<script>
    import {meta, router} from 'tinro';
    import api from "../../js/api.js";
    import { user } from '../../js/store.js'
    import Form from '../common/form/Form.svelte';
    import TextInput from '../common/form/input/TextInput.svelte';
    import Submit from '../common/form/Submit.svelte';
    import Modal from '../common/Modal.svelte';
    import {onMount} from 'svelte';


    let forcedChange = 'Test';
    let route = meta();

    function onSubmit() {
        api.user.logout();
        router.goto( '/zuchtbuch/1' );
    }

    function cancel() {
        router.goto( route.from );
    }

    onMount( () => forcedChange = 'changed' );
</script>

<Modal class=''>
    <Form class='w-96 flex flex-col self-center rounded pt-16' autoSave={false} on:submit={onSubmit}>
        <div class='flex rounded-t bg-header p-4'>
            <h2 class='grow text-white'>Abmelden</h2>
            <div class='w-8 justify-center m-2 circled bg-alert cursor-pointer' on:click={cancel}>X</div>
        </div>
        <div class='flex flex-col gap-4 rounded-b bg-gray-200  p-4'>
            <div class='h-16 text-center'>{ $user.email }</div>
            <!-- Submit value='Ok' /-->
            <Submit submitValue='Ich will raus !' enforce={true}/>
        </div>
    </Form>

</Modal>
<style>

</style>
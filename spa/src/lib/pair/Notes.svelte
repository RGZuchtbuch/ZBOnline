<script>

    import TextArea from '../common/form/input/TextArea.svelte';
    import Brood from "./Brood.svelte";
    import FormStatus from '../common/form/Status.svelte';
    export let notes;
    export let disabled;
    export let invalid = false;


    invalid = false; // always
</script>


<fieldset class='flex flex-col border rounded border-gray-400'>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow'>Notizen</div>
        <div class='w-6'></div>
    </div>

    <div class='flex justify-end'>
        <FormStatus />
    </div>
    <div class='flex flex-col p-2 gap-x-1'>
        <TextArea class='grow' label={'Notizen'} bind:value={notes} />
    </div>
</fieldset>

<style>

</style>
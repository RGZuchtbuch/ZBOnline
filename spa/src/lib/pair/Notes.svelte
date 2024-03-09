<script>

    import TextArea from '../common/form/input/TextAreaInput.svelte';
    export let notes;
    export let disabled;
    export let invalid = false;


    invalid = false; // always
</script>

<div class='flex flex-col my-2'>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow'>Notizen</div>
        <div class='w-6'></div>
    </div>

    <div class='flex flex-row p-2 gap-x-1'>
        <TextArea class='grow' label={'Notizen'} bind:value={notes} />
    </div>
</div>

<style>

</style>
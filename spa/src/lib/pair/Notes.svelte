<script>
    export let notes;
    export let disabled;
    export let invalid = false;


    invalid = false; // always
</script>

<div class='flex flex-col my-2'>
    <h4>Notizen</h4>

    <div class='flex flex-row gap-x-1'>
        <textarea class='grow' label={'Notizen'} bind:value={notes} {disabled}/>
    </div>
</div>

<style>

</style>
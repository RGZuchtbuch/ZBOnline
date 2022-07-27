<script>
    import InputDate from '../input/Date.svelte';
    import InputNumber from '../input/Number.svelte';
    import InputRing from '../input/Ring.svelte';
    import InputText from '../input/Text.svelte';
    import Select from '../select/Select.svelte';

    export let report;
    export let disabled;

    function addBrood() {
        console.log( 'Add Parent' );
        report.broods = [...report.broods, { start:null, eggs:null, fertile:null, hatched:null }];
    }

    function removeBrood( index ) {
        return () => {
            report.broods.splice(index, 1);
            report.broods = report.broods; // trigger
        }
    }

</script>

<div class='flex flex-col my-2'>
    <div>Brutleistung</div>

    <div class='flex flex-col gap-y-1'>
        {#each report.broods as brood, i}
            <div class='flex flex-row gap-x-1'>
                <InputDate class='w-24' label={i===0 ? 'Eingelegt' : null } bind:value={brood.start} {disabled} />
                <input type='button' on:click={removeBrood(i)} value='X'>
            </div>

        {/each}
        <div class='rounded border bg-gray-500 text-center text-white cursor-pointer' on:click={addBrood}>Brut zufügen</div>
    </div>

</div>

<style>

</style>
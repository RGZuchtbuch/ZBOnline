<script>
    import InputButton from '../input/Button.svelte';
    import InputDate from '../input/Date.svelte';
    import InputNumber from '../input/Number.svelte';
    import InputRing from '../input/Ring.svelte';
    import InputText from '../input/Text.svelte';
    import Select from '../select/Select.svelte';

    export let report;
    export let disabled;

    function addBrood() {
        console.log( 'Add Parent' );
        report.broods = [...report.broods, { id:Date.now(), start:null, eggs:null, fertile:null, hatched:null }];
    }

    function removeBrood( index ) {
        return () => {
            console.log( 'Brood', index, report.broods );
            report.broods.splice(index, 1);
            report.broods = report.broods; // trigger
            console.log( 'Brood', index, report.broods );
        }
    }

</script>

<div class='flex flex-col my-2'>
    <div>Brutleistung</div>

    <div class='flex flex-col gap-y-1'>
        {#each report.broods as brood, i (brood.id)}
            <div class='flex flex-row gap-x-1'>
                <InputNumber class='w-8' label={i===0 ? '#' : null} value={i} readonly />
                <InputDate class='w-24' label={i===0 ? 'Am' : null } bind:value={brood.start} {disabled} />
                <InputNumber class='w-16' label={i===0 ? 'Eingelegt' : null } bind:value={brood.eggs} min=1 max={99999} {disabled} />
                <InputNumber class='w-16' label={i===0 ? 'Befruchtet' : null } bind:value={brood.fertile} min=0 max={brood.eggs} error={0+' - '+brood.eggs} {disabled} />
                <InputNumber class='w-16' label={i===0 ? 'Geschlüpft' : null } bind:value={brood.hatched} min=0 max={brood.fertile} error={0+' - '+brood.fertile} {disabled} />
                <InputButton class='w-8' on:click={removeBrood(i)} label={i===0 ? 'Entf' : null} value='X' readonly />
            </div>

        {/each}
        <div class='rounded border bg-gray-500 text-center text-white cursor-pointer' on:click={addBrood}>Brut zufügen</div>
    </div>

</div>

<style>

</style>
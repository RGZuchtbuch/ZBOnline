<script>
    import { getProduction, getValidDate } from '../../js/util.js'

    import InputDate from '../input/Date.svelte';
    import InputNumber from '../input/Number.svelte';
    import InputRing from '../input/Ring.svelte';
    import InputText from '../input/Text.svelte';
    import Select from '../select/Select.svelte';

//    export let result;
    export let notes;
    export let disabled;

</script>

<div class='flex flex-col my-2'>
    <h4>Notizen</h4>

    <div class='flex flex-row gap-x-1'>
        <textarea class='grow' label={'Notizen'} bind:value={notes} {disabled}/>
    </div>
</div>

<style>

</style>
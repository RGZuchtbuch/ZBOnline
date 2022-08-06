<script>
    import { getProduction, getValidDate } from '../../js/util.js'

    import InputDate from '../input/Date.svelte';
    import InputNumber from '../input/Number.svelte';
    import InputRing from '../input/Ring.svelte';
    import InputText from '../input/Text.svelte';
    import Select from '../select/Select.svelte';

    export let show;
    export let disabled;

    let count = 0;
    let score = null;

    $: update( show );

    function update( show ) {
        count = show.p89 + show.p90 + show.p91 + show.p92 + show.p93 + show.p94 + show.p95 + show.p96 + show.p97;
        score = count
            ? ( (show.p89 * 89 + show.p90 * 90 + show.p91 * 91 + show.p92 * 92 + show.p93 * 93 + show.p94 * 94 + show.p95 * 95 + show.p96 * 96 + show.p97 * 97) / count ).toFixed(1)
            : null;
        console.log( 'Update', count, score );
    }

</script>

<div class='flex flex-col my-2'>
    <h4>Schauleistung</h4>

    <div class='flex flex-row gap-x-1'>
        <InputNumber class='w-12' label={'U/O'} bind:value={show.p89} {disabled}/>
        <InputNumber class='w-12' label={'90'} bind:value={show.p90} {disabled}/>
        <InputNumber class='w-12' label={'91'} bind:value={show.p91} {disabled}/>
        <InputNumber class='w-12' label={'92'} bind:value={show.p92} {disabled}/>
        <InputNumber class='w-12' label={'93'} bind:value={show.p93} {disabled}/>
        <InputNumber class='w-12' label={'94'} bind:value={show.p94} {disabled}/>
        <InputNumber class='w-12' label={'95'} bind:value={show.p95} {disabled}/>
        <InputNumber class='w-12' label={'96'} bind:value={show.p96} {disabled}/>
        <InputNumber class='w-12' label={'97'} bind:value={show.p97} {disabled}/>
        <InputNumber class='w-16' label={'# Tiere'} value={ count } readonly />
        <InputNumber class='w-20' label={'∅ Note'} value={ score } readonly />
    </div>
</div>

<style>

</style>
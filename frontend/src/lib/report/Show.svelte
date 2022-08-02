<script>
    import { getProduction, getValidDate } from '../../js/util.js'

    import InputDate from '../input/Date.svelte';
    import InputNumber from '../input/Number.svelte';
    import InputRing from '../input/Ring.svelte';
    import InputText from '../input/Text.svelte';
    import Select from '../select/Select.svelte';

    export let report;
    export let disabled;

    function count( show ) {
        return show.p89 + show.p91 + show.p92 + show.p93 + show.p94 + show.p95 + show.p96 + show.p97;
    }
    function score( show ) {
        return (show.p89*89 + show.p91*91 + show.p92*92 + show.p93*93 + show.p94*94 + show.p95*95 + show.p96*96 + show.p97*97) / count( show );
    }

</script>

<div class='flex flex-col my-2'>
    <div>Schauleistung</div>

    <div class='flex flex-row gap-x-1'>
        <InputNumber class='w-12' label={'U/O'} bind:value={report.show.p89} {disabled}/>
        <InputNumber class='w-12' label={'90'} bind:value={report.show.p90} {disabled}/>
        <InputNumber class='w-12' label={'91'} bind:value={report.show.p91} {disabled}/>
        <InputNumber class='w-12' label={'92'} bind:value={report.show.p92} {disabled}/>
        <InputNumber class='w-12' label={'93'} bind:value={report.show.p93} {disabled}/>
        <InputNumber class='w-12' label={'94'} bind:value={report.show.p94} {disabled}/>
        <InputNumber class='w-12' label={'95'} bind:value={report.show.p95} {disabled}/>
        <InputNumber class='w-12' label={'96'} bind:value={report.show.p96} {disabled}/>
        <InputNumber class='w-12' label={'97'} bind:value={report.show.p97} {disabled}/>
        <InputText class='w-12' label={'Tiere'} value={ count( report.show )} readonly />
        <InputText class='w-12' label={'Tiere'} value={ score( report.show ).toFixed(1)} readonly />
    </div>
</div>

<style>

</style>
<script>
    import InputNumber from '../common/input/Number.svelte';

    export let pair;
    export let disabled;

    let count = 0;
    let avgScore = null;




    function update( pair ) {
        if( ! pair.show ) {
            pair.show = { 89:null, 90:null, 91:null, 92:null, 93:null, 94:null, 95:null, 96:null, 97:null };
        }
        updateResult();
    }

    function updateResult() {
        count = 0; // count nr of scores
        let total = 0; // for scores * points
        let keys = [89, 90, 91, 92, 3, 94, 95, 96, 97]; // dont need all keys from show !
        for (let key of keys) {
            let value = pair.show[key];
            if (value) { // not null or 0
                count += value; // score can be null;
                total += value * key; // add tot total points
            } else {
                //pair.show[key] = null; //no value or 0
            }
        }
        avgScore = count > 0 ? total / count : null; // to average score
    }

    $: update( pair );

</script>

<div class='flex flex-col border rounded border-gray-400'>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow'>Schauleistung</div>
        <div class='w-6'></div>
    </div>

    <div class='grow flex flex-row p-2 gap-x-1'>
        {#if pair.show}
            <InputNumber class='w-12' label={'U/O'} name='count' bind:value={pair.show['89']} min=0 max=999 />
            <InputNumber class='w-12' label={'90'} name='count' bind:value={pair.show['90']} min=0 max=999 {disabled}/>
            <InputNumber class='w-12' label={'91'} name='count' bind:value={pair.show['91']} min=0 max=999 {disabled}/>
            <InputNumber class='w-12' label={'92'} name='count' bind:value={pair.show['92']} min=0 max=999 {disabled}/>
            <InputNumber class='w-12' label={'93'} name='count' bind:value={pair.show['93']} min=0 max=999 {disabled}/>
            <InputNumber class='w-12' label={'94'} name='count' bind:value={pair.show['94']} min=0 max=999 {disabled}/>
            <InputNumber class='w-12' label={'95'} name='count' bind:value={pair.show['95']} min=0 max=999 {disabled}/>
            <InputNumber class='w-12' label={'96'} name='count' bind:value={pair.show['96']} min=0 max=999 {disabled}/>
            <InputNumber class='w-12' label={'97'} name='count' bind:value={pair.show['97']} min=0 max=999 {disabled}/>
            <div class='grow'></div>
            <InputNumber class='w-16' label={'# Tiere'} value={ count } disabled readonly />
            <InputNumber class='w-16' label={'∅ Note'} value={ avgScore ? avgScore.toFixed(1) : null } disabled readonly />
        {/if}
    </div>
</div>

<style>

</style>
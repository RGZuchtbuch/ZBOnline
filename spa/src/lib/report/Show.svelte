<script>
    import InputNumber from '../common/input/Number.svelte';

    export let show;
    export let disabled;

    let count = 0;
    let avgScore = null;

    $: update( show );

    function update( show ) {
        console.log( 'Update show');
        count = 0; // count nr of scores
        let points = 0; // for scores * points
        let keys = [ 89,90,91,92,3,94,95,96,97 ];
        for( let key of keys ) {
            let value = show[ key ];
            if( value ) { // not null or 0
                count += value; // score can be null;
                points += value * key;
            } else {
                show[ key ] = null; //no value or 0
            }
        }
        avgScore = count > 0 ? points / count : null; // to average score
    }

</script>

<div class='flex flex-col my-2'>
    <h4 class='bg-gray-300'>Schauleistung</h4>

    <div class='flex flex-row gap-x-1'>
        <InputNumber class='w-12' label={'U/O'} name='count' bind:value={show['89']} min=0 max=999 {disabled}/>
        <InputNumber class='w-12' label={'90'} name='count' bind:value={show['90']} min=0 max=999 {disabled}/>
        <InputNumber class='w-12' label={'91'} name='count' bind:value={show['91']} min=0 max=999 {disabled}/>
        <InputNumber class='w-12' label={'92'} name='count' bind:value={show['92']} min=0 max=999 {disabled}/>
        <InputNumber class='w-12' label={'93'} name='count' bind:value={show['93']} min=0 max=999 {disabled}/>
        <InputNumber class='w-12' label={'94'} name='count' bind:value={show['94']} min=0 max=999 {disabled}/>
        <InputNumber class='w-12' label={'95'} name='count' bind:value={show['95']} min=0 max=999 {disabled}/>
        <InputNumber class='w-12' label={'96'} name='count' bind:value={show['96']} min=0 max=999 {disabled}/>
        <InputNumber class='w-12' label={'97'} name='count' bind:value={show['97']} min=0 max=999 {disabled}/>
        <InputNumber class='w-16' label={'# Tiere'} value={ count } readonly />
        <InputNumber class='w-16' label={'∅ Note'} value={ avgScore ? avgScore.toFixed(1) : null } readonly />
    </div>
</div>

<style>

</style>
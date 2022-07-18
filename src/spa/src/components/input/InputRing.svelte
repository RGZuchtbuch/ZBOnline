<script>
    import InputYear from './InputYear.svelte';

    export let value = '';
    export let disabled = true;

    let className = ''
    export { className as class }

    let ring = {
        year:value.year,
        country:value.country,
        code:value.code,
    }

    // https://www.kleindierliefhebbers.nl/57-organisatie/ringenbureau
    const countries = [ 'A', 'B', 'BIH', 'CH', 'CZ', 'D', 'DR', 'E', 'F', 'NPA', 'H', 'I', 'YU', 'HR', 'NF', 'NL-H', 'P', 'SK', 'SLO' ];

    function onYear( event ) {
        value.year = ring.year;
        value = value;
    }

    function onCode( event ) {
        value.code = format( ring.code );
        value = value;
    }

    function format( code ) {
        return code;
    }
</script>

<div class="'flex flex-row gap-1">
<select class={'w-10 '+className} bind:value={value.country} {disabled}>
    {#each countries as country}
        <option value={country}>{country}</option>
    {/each}
</select>

<InputYear class={'w-10 '+className} bind:value={value.year} {disabled}/>{value.year}l

<input class={'w-16 '+className} type='text' maxlength='8' bind:value={value.code} on:input={onCode} placeholder='AZ 999' {disabled}>
</div>



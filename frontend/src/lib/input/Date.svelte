<script>
    import { getValidDate, printDate } from '../../js/util.js'

    export let value;
    export let label = null;
    export let name = null;
    export let disabled = false;
    export let readonly = false;
    export let required = false;
    export let min = settings.date.min;
    export let max = settings.date.max;
    export let error = new Date( min ).getFullYear() + ' - ' + new Date( max ).getFullYear();
    console.log('Min', min );

    let classname = '';
    export { classname as class }

    let input = printDate( value );
    let date = null;
    let invalid = false;

    let on = {
        focus: () => {},
        blur: () => {},
    }

    $: validate( input, min, max );

    function validate( input, min, max ) {
        const oldDate = date;
        date = getValidDate( input, min, max ); // null or valid
        if( date !== oldDate ) {
            value = date;
            invalid = false;
        } else {
            value = null;
            invalid = required || ! ( input === '' || input === null ); // true if required and wrong or not required and empty
        }
//        console.log( 'Date', label, input, min, max, invalid );
    }

</script>

<div class='input {classname} flex flex-col gap-0' title='Datum : 31.1.2021'>
    {#if label}
        <label class='label' for='input'>{label}</label>
    {/if}
    <input class='data' class:invalid id='input' type='text' {name}
           bind:value={input}
           {disabled} {readonly}
           {required}
           on:focus={on.focus}
           on:blur={on.blur}
    >
        <span class='invalid'>
            {#if invalid && ! disabled}
                {error}
            {/if}
        </span>
</div>

<style>
</style>
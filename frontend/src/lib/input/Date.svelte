<script>
    import { getValidDate } from '../../js/util.js'

    export let value;
    export let label;
    export let name;
    export let disabled = false;
    export let readonly = false;
    export let required = false;
    export let min = settings.date.min;
    export let max = settings.date.max;
    export let error = new Date( min ).getFullYear() + ' - ' + new Date( max ).getFullYear();
    console.log('Min', min );

    let classname = '';
    export { classname as class }

    let input = toGermanDate( value );
    let date = null;
    let invalid = false;

    let on = {
        focus: () => {},
        blur: () => {},
    }

    $: validate( input, min, max );
    $: console.log( 'input changed', input );
    $: console.log( 'min changed', min );
    $: console.log( 'max changed', max );

    function toGermanDate( date ) {
        if( date ) {
            date = new Date(date);
            return date.getDate() + '.' + (date.getMonth() + 1) + '.' + date.getFullYear();
        }
        return null;
    }

    function validate( input, min, max ) {
        console.log( 'Date', label, input, min, max );
        const oldDate = date;
        date = getValidDate( input, min, max ); // null or valid
        if( date !== value ) {
            value = date;
            invalid = false;
        } else {
            value = null;
            invalid = required || ! ( input === '' || input === null );
        }
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
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
    export let error = min.getFullYear()+' - '+max.getFullYear();

    let classname = '';
    export { classname as class }

    let input = value;
    let invalid = false;

    let on = {
        focus: () => {},
        blur: () => {},
    }

    $: validate( input );



    function validate() {
        const date = getValidDate( input, min, max );
        if( date ) {
            value = input;
            invalid = false;
        } else {
            invalid = required || ! ( input === '' || input === null );
        }
    }

</script>

<div class='input {classname} flex flex-col gap-0'>
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
            &nbsp;
            {#if invalid && ! disabled}
                {error}
            {/if}
        </span>
</div>

<style>
</style>
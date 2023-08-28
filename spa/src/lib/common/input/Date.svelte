<script>
    import { toDate, formatDate } from '../../../js/util.js'

    export let value;
    export let label = null;
    export let name = null;
    export let disabled = false;
    export let readonly = false;
    export let required = false;
    export let min = MINDATE; //settings.date.min;
    export let max = MAXDATE; //settings.date.max;
    export let error = new Date( min ).getFullYear() + ' - ' + new Date( max ).getFullYear();
    export let invalid = false;
    export let element;

    let classname = '';
    export { classname as class }

    let input = formatDate( 'D', value );
//    let date = null;
//    let invalid = false;

    let on = {
        focus: () => {},
        blur: () => {},
    }

    function validate() {
        invalid = false;
        if( input && input.length>0 ) {
            value = toDate( input, min, max );
            if( ! value ) invalid = true;
        } else {
            value = null;
            invalid = required;
        }
//        invalid = value ? false : required;
        console.log( 'Date val', value, invalid )
    }

    function onInput( event ) {
        input = event.target.value;
        validate();
    }

    $: validate( min, max, required );

//    $: validate( input, min, max );

</script>

<div class='input {classname} flex flex-col gap-0' title='Datum : 31.1.2021'>
    {#if label}
        <label class='label' for='input'>{label} {required?'*':''}</label>
    {/if}
    <input class='data' class:invalid id='input' type='text' {name}
           bind:value={input} bind:this={element}
           {disabled} {readonly}
           {required}
           on:focus={on.focus}
           on:blur={on.blur}
           on:input={onInput}
    >
    <span class:invalid>
        {#if invalid} {error} {:else} &nbsp; {/if}
    </span>
</div>

<style>
</style>
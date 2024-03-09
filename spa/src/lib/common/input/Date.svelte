<script>
    import { toDate, formatDate, dat } from '../../../js/util.js'

    export let value; // in iso 2023-10-13
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
    export let title = null;

    let classname = '';
    export { classname as class }

    let input = dat( value ); //

    let on = {
        focus: () => {},
        blur: () => {},
    }

    function validate() {
        invalid = false;
        if( input && input.length > 0 ) { // any input
            value = toDate( input, min, max ); // valid date
            invalid = ! value; // t/f
        } else { // empty -< null
            value = null; // no input
            invalid = required;
        }
    }

//    function onInput( event ) {
        //input = event.target.value;
//        validate();
//    }


    $: validate( input, min, max, required );

</script>

<div class='input {classname} flex flex-col gap-0' title={title || 'Datum : 31.1.2021'}>
    {#if label}
        <label class='label' for='input'>{label} {required?'*':''}</label>
    {/if}
    <input class='data' class:invalid id='input' type='text' {name}
           bind:value={input} bind:this={element}
           {disabled} {readonly}
           {required}
           on:focus={on.focus}
           on:blur={on.blur}
    >
    <span class:invalid>
        {#if invalid} {error} {:else} &nbsp; {/if}
    </span>
</div>

<style>
</style>
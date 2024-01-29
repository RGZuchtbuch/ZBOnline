<script>

    export let value = null;
    export let label = null;
    export let error = 'ungültiger Passwort';
    export let name = null;
    export let disabled = false;
    export let readonly = false;
    export let maxlength = 128;
    export let minlength = 3;
    export let required = false;
    export let invalid = false;
    export let element = null;
    export let title = 'Passwort';

    let classname = '';
    export { classname as class }


    let on = {
        focus: () => {},
        blur: () => {},
    }

    $: validate( value );

    function validate( value ) {
        if( value ) {
            invalid = value.length < 4;
        } else {
            invalid = required;
        }
    }

    $: validate( value );

</script>

<div class='input {classname} flex flex-col gap-0' {title}>
    {#if label}
        <label class='label' for='pwdinput'>{label}</label>
    {/if}
    <input class='data' class:invalid id='pwdinput' type='password' {name} bind:value={value} bind:this={element}
           {minlength} {maxlength}
           {required} {disabled} {readonly}

           on:focus={on.focus}
           on:blur={on.blur}
           autocomplete='on'

    >
    <span class:invalid>
        {#if invalid} {error} {:else} &nbsp; {/if}
    </span>
</div>

<style>

</style>
<script>

    export let value = null;
    export let label = null;
    export let error = '!';
    export let name = null;
    export let title = null;
    export let disabled = false;
    export let readonly = false;
    export let required = false;

    export let element;

    let classname = '';
    export { classname as class }

    let invalid = false;

    let on = {
        focus: () => {},
        blur: () => {},
    }

    $: validate( value )

    function validate() {
        invalid = required ? value===null : false;
    }

</script>

<div class='input {classname} flex flex-col gap-0' {title}>
    {#if label}
        <label class='label' for='input'>{label}</label>
    {/if}

    <select class:invalid id='input'
            bind:value={value} bind:this={element}
            {name}
            {disabled} {readonly} {required}
            on:focus={on.focus}
            on:blur={on.blur}
            on:change
    >
            <slot></slot>
    </select>

    <span class:invalid>
        {#if invalid} {error} {:else} &nbsp; {/if}
    </span>
</div>

<style>
</style>

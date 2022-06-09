<script>

    export let legend='';
    export let href=null;

</script>

<fieldset>
    <legend>
        {legend}
        {#if href }
            <a href={href}> ← </a>
        {/if}
    </legend>
    <slot></slot>
</fieldset>

<style>
    fieldset {
        @apply border rounded border-gray-600 px-2 bg-gray-50
    }

    fieldset legend {
        @apply ml-4 text-xl;
    }
</style>
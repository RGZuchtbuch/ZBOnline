<script>

    export let legend='';

</script>

<fieldset>
    <legend>{legend}</legend>
    <slot></slot>
</fieldset>

<style>
    fieldset {
        @apply border rounded border-gray-600
    }

    fieldset legend {
        @apply ml-4 px-2 pb-2 text-xl  ;
    }
</style>
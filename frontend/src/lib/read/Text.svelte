<script>

    export let value;
    export let label;

    let classname = '';
    export { classname as class }

    value = value ? value.toString() : null;


</script>

<div class='input {classname} flex flex-col gap-0'>
    {#if label}
        <label class='label' for='read'>{label}</label>
    {/if}
    <div id='read'>{value}</div>
</div>

<style>
    #read {
        @apply text-right;
    }

</style>
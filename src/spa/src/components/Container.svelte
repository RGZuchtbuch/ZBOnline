<script>

</script>

<div class='h-full border'>
    <slot></slot>
</div>

<style>
    div {
        display: grid;
        grid-template-rows: auto;
        grid-template-columns: auto;
    }

    div > :global(*) {
        grid-row: 1;
        grid-column: 1;
        margin: 1em auto;
    }
</style>
<script module>
    export { name, test };
</script>


{#snippet name( breeder )}
    {#if breeder}
        {breeder.firstname} {breeder.infix} {breeder.lastname}
    {/if}
{/snippet}

{#snippet test()}
    <span>TEST</span>
{/snippet}
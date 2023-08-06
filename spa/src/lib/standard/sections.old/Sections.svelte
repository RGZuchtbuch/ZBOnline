<script>
    import SectionRow from './SectionRow.svelte';

    export let sections = null;
</script>


{#if sections}
    <div class='w-256 bg-gray-100 overflow-y-scroll border rounded-b border-t-0 border-gray-400 px-4 scrollbar'>
        {#each sections as section}
                <SectionRow section={section} open={true} />
        {/each}
    </div>
{/if}

<style>

</style>
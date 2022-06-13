<script>
    import Box from '../components/Box.svelte';
    import SelectTreeNode from '../components/SelectTreeNode.svelte';

    export let root;
    export let onSelect = null

</script>


<Box legend={root.name}>
    <div class='flex flex-col'>
        {#each root.children as node}
            <SelectTreeNode node={node} onSelect={onSelect}/>
        {/each}
    </div>
</Box>
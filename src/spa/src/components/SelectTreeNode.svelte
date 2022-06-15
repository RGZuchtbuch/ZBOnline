<script>
    export let children;
    export let onSelect = null;

    let selectable = onSelect;

    function onClick( node ) {
        return ( event ) => {
            console.log('Clicked', node.name);
            if( onSelect ) {
                onSelect( node );
            }
        }
    }
</script>

<ul>
    {#each children as node}
        <li on:click={onClick(node)} class:selectable nowrap>→ {node.name}</li>
        {#if node.children}
            <svelte:self children={node.children} {onSelect}/>
        {/if}
    {/each}
</ul>

<style>
    .selectable {
        @apply cursor-pointer whitespace-nowrap;
    }

    ul {
        @apply ml-2;
    }
    li {}
</style>
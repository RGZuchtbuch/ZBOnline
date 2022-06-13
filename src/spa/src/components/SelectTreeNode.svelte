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

{#each children as node}
    <div on:click={onClick(node)} class:selectable>→ {node.name}</div>
    {#if node.children}
        <div class='ml-2'>
            <svelte:self children={node.children} {onSelect}/>
        </div>
    {/if}
{/each}

<style>
    .selectable {
        @apply cursor-pointer;
    }
</style>
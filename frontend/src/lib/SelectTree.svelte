<script>
    export let children;
    export let onSelect = null;
    export let link = '';

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
        <li class:selectable nowrap>
            &#10551; <a href={link+node.id}>{node.name}</a>
        </li>
        {#if node.children}
            <svelte:self children={node.children} {onSelect} link={link}/>
        {/if}
    {/each}
</ul>

<style>
    .selectable {
        @apply cursor-pointer whitespace-nowrap;
    }

    ul {
        @apply ml-4;
    }
    li {}
</style>
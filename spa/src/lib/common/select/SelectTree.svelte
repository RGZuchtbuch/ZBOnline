<script>
    export let children;
    export let onSelect = null;
    export let link = '';

    let selectable = onSelect;


    function onClick( node ) {
        return ( event ) => {
            if( onSelect ) {
                onSelect( node );
            }
        }
    }
</script>

<ul>
    {#each children as child}
        <li class:selectable nowrap>
            &#10551; <a href={link+child.id}>{child.name}</a>
        </li>
        {#if child.children}
            <svelte:self children={child.children} {onSelect} link={link}/>
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
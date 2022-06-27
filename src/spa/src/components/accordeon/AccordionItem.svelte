<script>
    import { getContext } from 'svelte';

    export let label;
    export let selected = false;

    let inClass = ''
    export { inClass as class }


    const accordion = getContext( 'accordion' )
    $: console.log( 'Got', $accordion );

    let id = $accordion.id++;

    accordion.update( value => {
        value.selected = id;
        return value;
    })

    function onClick( event ) {
        console.log( 'onClick', id);
        accordion.update( value => {
            value.selected = id;
            return value;
        })
    }

    $: console.log( id, selected, $accordion.selected );
</script>


<main>
    <div class={'w-full cursor-pointer bg-gray-200 p-2 flex flex-row justify-between '+inClass} on:click={onClick}>
        <div>{label} [{id}]</div>
        <div>{#if $accordion.selected === id}-{:else}+{/if}</div>
    </div>

    {#if $accordion.selected === id}
        <div class='w-full p-4'>
            <slot />
        </div>
    {/if}
</main>

<style>
    div {

    }
</style>


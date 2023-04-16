<script>
    import api from "../../js/api.js";

    export let breed = null;

    let colors = null;

    function onColors() {
        console.log( 'OnColors');
        if( colors ) {
            colors = null;
        } else {
            api.breed.colors.get( breed.id ).then( response => {
                colors = response.colors;
            });
        }
    }

</script>

<div class='pl-6'>
    <div class='flex'>
        <div>&#10551; &#174; {breed.name} </div>
    {#if colors}
        <span class='button text-red-600' on:click={onColors}>[ &#8628; ]</span>
    {:else}
        <span class='button text-green-600' on:click={onColors}>[ &#8628; ]</span>
    {/if}
    </div>

    {#if colors}
        {#each colors as color}
            <div class='px-6'> &#10551; &#169; {color.name} </div>
        {/each}
    {/if}

</div>

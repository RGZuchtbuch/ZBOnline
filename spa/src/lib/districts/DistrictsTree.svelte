<script>
    import {meta} from "tinro";
    import Toggler from '../common/OpenClose.svelte';

    export let district;
    export let open = false;

    function toggleOpen() {
        open = ! open;
    }

    let moderated = district.moderated;
    let show = false;
    let edit = false;
    let details = null;
    let changed = false;
    let children = null;
    let breeders = null;

    const route    = meta();

</script>

{#if district}
    <div class='flex flex-col pl-8'>
        <div class='flex border-b border-gray-300 my-2'>
            <Toggler bind:open={open} enabled={district.children.length > 0} class='text-orange-600'/>
            {#if district.moderated}
                <a class='cursor-pointer' class:moderated href={route.match+'/'+district.id} title='Zum Verband'>{district.name} </a>
            {:else}
                <span class='cursor-not-allowed'>{district.name}</span>
            {/if}
            <small class='w-8 text-center'> [{district.children.length}]</small>
            <div class='grow'></div>
            <small class='w-8 text-right'>[{district.id}]</small>
        </div>

        {#if district && ( open )}
            {#each district.children as childDistrict}
                <svelte:self district={childDistrict} open={true}/>
            {/each}
        {/if}

    </div>
{/if}



<style>
    .moderated {
        @apply font-bold;
    }
</style>
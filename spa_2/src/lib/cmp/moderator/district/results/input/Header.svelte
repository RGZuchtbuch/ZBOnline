<script>
    let { section } = $props();

    const PIGEONS = 5;
//    export let sectionId = 3;
</script>



<div class='w-full flex flex-col header'>
    <div class='flex flex-row p-2 gap-x-1 text-sm font-bold'>
        {#if section && section.id}
            <div class='w-80'>Rasse {section.id === PIGEONS ? '' : ' & Farbe'}</div>
            <div class='w-4'></div>


            {#if section.id === PIGEONS }
                <div class='w-28 text-center'>Zucht</div> <div class='w-4'></div>
            {:else}
                <div class='w-14 text-center'>Zucht</div> <div class='w-14'></div> <div class='w-2'></div>
            {/if}
            <!-- lay -->
            {#if section.id === PIGEONS }
                <div class='w-28'></div>
                <div class='w-2'></div>
            {:else}
                <div class='w-28 text-center'>Legeleistung</div>
                <div class='w-2'></div>
            {/if}

            <!-- brood -->
            {#if section.id === PIGEONS }
                <div class='w-44 text-center'>Brutleistung</div>
                <div class='w-2'></div>
            {:else}
                <div class='w-44 text-center'>Brutleistung</div>
                <div class='w-2'></div>
            {/if}

            <div class='w-28 text-center'>Schauleistung</div>

        {/if}
    </div>
</div>
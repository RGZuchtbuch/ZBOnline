<script>
    export let sectionId = 3;
</script>



<div class='w-256 flex flex-col bg-header'>
    <div class='flex flex-row p-2 gap-x-1 text-sm font-bold text-white'>
        {#if sectionId}
            <div class='w-80'>Rasse {sectionId === 5 ? '' : ' & Farbe'}</div>
            <div class='w-4'></div>


            {#if sectionId === PIGEONS }
                <div class='w-28 text-center'>Zucht</div> <div class='w-4'></div>
            {:else}
                <div class='w-14 text-center'>Zucht</div> <div class='w-14'></div> <div class='w-2'></div>
            {/if}
            <!-- lay -->
            {#if sectionId === PIGEONS }
                <div class='w-28'></div>
                <div class='w-2'></div>
            {:else}
                <div class='w-28 text-center'>Legeleistung</div>
                <div class='w-2'></div>
            {/if}

            <!-- brood -->
            {#if sectionId === PIGEONS }
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
<script>
    export let sectionId = 3;
    export let saveCount = 0;

</script>



<div class='w-256 flex flex-col bg-header'>
    <div class='flex flex-row p-2 gap-x-1 text-sm font-bold text-white'>
        {#if sectionId}
            <div class='w-64'>Rasse {sectionId === 5 ? '' : ' & Farbe'}</div>
            <div class='w-4'></div>

            <div class='w-28 text-center'>Zucht</div>

            <div class='w-4 text-center'></div>

            {#if sectionId !== 5 }
                <div class='w-44 text-center'>Legeleistung</div>
                <div class='w-1 text-center'></div>
            {/if}

            {#if sectionId === 5 }
                <div class='w-28 text-center'>Brutleistung</div>
                <div class='w-4 text-center'></div>
            {:else}
                <div class='w-44 text-center'>Brutleistung</div>
                <div class='w-3 text-center'></div>
            {/if}

            <div class='w-28 text-center'>Schauleistung</div>

            {#if saveCount > 0 }
                <div class='px-4 rounded bg-alert text-white'> # { saveCount }</div>
            {/if}
        {/if}
    </div>
</div>
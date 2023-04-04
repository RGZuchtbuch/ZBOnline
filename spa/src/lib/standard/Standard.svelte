<script>
    import api from '../../js/api.js';
    import SectionRow from './SectionRow.svelte';

    let standard = null;


    function loadStandard() {
        api.standard.get().then( response => {
            console.log( response );
            standard = response.standard;
        })
    }

    loadStandard();
</script>


<h2 class='w-256 border border-gray-400 rounded-t p-2 bg-header text-center text-xl print'>BDRG Standard (in progress)</h2>
{#if standard}
    <div class='w-256 bg-gray-100 overflow-y-scroll border rounded-b border-t-0 border-gray-400 px-4 scrollbar'>
        <SectionRow section={standard} open={true}/>
    </div>
{/if}

Standard

<style>

</style>
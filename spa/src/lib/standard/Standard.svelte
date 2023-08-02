<script>
    import api from '../../js/api.js';
    import Sections from './sections/Sections.svelte';
    import SectionRow from './sections/SectionRow.svelte';

    let rootSection = null;


    function loadStandard() {
        api.standard.get().then( response => { rootSection = response.standard; } );
    }

    loadStandard();
</script>


<h2 class='w-256 border border-gray-400 rounded-t p-2 bg-header text-white text-center text-xl print'>BDRG Rassegeflügelstandard</h2>
{#if rootSection}
    <Sections sections={rootSection.children}/>
{/if}


<style>

</style>
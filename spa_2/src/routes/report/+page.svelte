<script>
    //import { getContext } from 'svelte';
    import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';
    import Report from '$lib/cmp/report/Report.svelte';

    const title = 'Die Zuchtleistungen';
    const menu = {
        trail : [
            { name:'Start',       href:'/' },
            { name:'Leistungen', href:'/result' },
        ],
        options : [
            { name:'Karte',       href:'/result/map/year/2024/type/2' },
            { name:'Trend',       href:'/result/trend/district/1/type/2' },
            { name:'Tabelle',       href:'/result/table/district/1/year/2024' },
        ],
    };

	store.title.update( () => title );
	store.menu.update( () => menu );

	// note: cmp Report Filter works the query and does the loading
</script>

{#if true }
	<Report />
{/if}



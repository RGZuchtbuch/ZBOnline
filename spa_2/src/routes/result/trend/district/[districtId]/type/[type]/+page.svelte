<script>
	import { state } from '$lib/js/store.svelte.js';
	import { onMount } from 'svelte';

	let { data } = $props();

	console.log( 'S', data.standard )

	onMount( () => {
        state.title = `Trend der Zuchtleistungen für ${data.districtId}  ${data.type}`;
		state.trail = [
			{ name:'Start',       href:'/' },
			{ name:'Leistungen', href:'/result' },
            { name:'Trend',       href:'/result/trend/district/1/type/2' },
		];
		state.options = [
            //{ name:'Trend',       href:'/result/trend/district/1/type/2' },
            { name:'Tabelle',       href:'/result/table/district/1/year/2024' },
            { name:'Karte',       href:'/result/map/year/2024/type/2' },
		];
	})

</script>

<p>
    Results Trend for district {data.districtId} and type {data.type}, initial BDRG and Zuchten
</p>

<ul>
    {#each [ 1, 2 ] as districtId}
        <li><a href={`/result/trend/${districtId}`}>{districtId}</a></li>
    {/each}

    {#each [ 1, 2 ] as districtId}
        <li><a href={`/result/trend/${districtId}`}>{districtId}</a></li>
    {/each}
</ul>

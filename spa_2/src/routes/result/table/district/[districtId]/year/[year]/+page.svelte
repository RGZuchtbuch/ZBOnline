<script>
	import { state } from '$lib/js/store.svelte.js';
	import { onMount } from 'svelte';

	let { data } = $props();

	console.log( 'S', data.standard )

	onMount( () => {
		state.title = `Zuchtleistungsdaten für ${data.districtId} ${data.year}`;
		state.trail = [
			{ name:'Start',       href:'/' },
			{ name:'Leistungen',  href:'/result' },
            { name:'Tabelle',     href:'/result/table/district/1/year/2024' },
		];
		state.options = [
            { name:'Karte',       href:'/result/map/year/2024/type/2' },
            { name:'Trend',       href:'/result/trend/district/1/type/2' },
           // { name:'Tabelle',       href:'/result/table/district/1/year/2024' },
		];
	})

</script>

<p>
    Results Table for district {data.districtId} and year {data.year}
</p>

<ul>
    {#each [ 2024, 2025 ] as year}
        <li><a href={`/result/map/${year}`}>{year}</a></li>
    {/each}
</ul>

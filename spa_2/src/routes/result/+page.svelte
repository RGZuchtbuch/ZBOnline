<script>
	import { state } from '$lib/js/store.svelte.js';
	import { onMount } from 'svelte';

	let { data } = $props();

	onMount( () => {
		state.title = 'Die Zuchtleistungen';
		state.trail = [
			{ name:'Start',       href:'/' },
			{ name:'Leistungen', href:'/result' },
		];
		state.options = [
			{ name:'Karte',       href:'/result/map/year/2024/type/2' },
			{ name:'Trend',       href:'/result/trend/district/1/type/2' },
			{ name:'Tabelle',       href:'/result/table/district/1/year/2024' },
		];
	})

</script>

Leistungen öffentlich per unfold
<p>Leistungen per sparte, rasse, farbe, gruppe und zuchten, legen, bruten H/T schau</p>
<br>
<ul>
    <li>Karte für Jahr / Leistung</li>
    <li>Trend für verband / leistung</li>
    <li>Tabelle für verband / jahr</li>
</ul>


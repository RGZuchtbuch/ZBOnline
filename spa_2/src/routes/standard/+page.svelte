<script>
	import { app } from '$lib/js/store.svelte.js';
	import BDRGMap from '$lib/cmp/district/BDRGMap.svelte';
	import DistrictTree from '$lib/cmp/district/DistrictTree.svelte';
	import Standard from '$lib/cmp/standard/Standard.svelte';

	let { data } = $props();

	app.title = 'Die anerkannten Rassen im BDRG';
	app.menu.trail = [
		{ name:'Start',      href:'/' },
		{ name:'Standard',   href:'/standard' },
	];
	app.menu.options = [];

</script>

<Standard standard={data.standard} />


<script>
	import { app } from '$lib/js/store.svelte.js';
	import BDRGMap from '$lib/cmp/district/BDRGMap.svelte';
	import DistrictTree from '$lib/cmp/district/DistrictTree.svelte';

	let { data } = $props();

	app.title = 'Die BDRG Verbände im Zuchtbuch';
	app.menu.trail = [
		{ name:'Start',      href:'/' },
		{ name:'Verbände',   href:'/district' },
	];
	app.menu.options = [];

</script>




<div class='flex flex-col items-center'>
	<BDRGMap width={384} />
	<hr>
	<DistrictTree root={data.districts.root}/>
</div>


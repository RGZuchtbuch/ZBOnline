<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';

	import BDRGMap from '$lib/cmp/district/districts/BDRGMap.svelte';
	import DistrictTree from '$lib/cmp/district/districts/DistrictTree.svelte';


	let { districtsRoot } = $props(); // root district with children

</script>

<section>
	<div class='flex flex-col items-center break-after-page' >
		<BDRGMap width={384} districts={districtsRoot.districts} />
	</div>
	<hr class='my-2'>
	<DistrictTree {districtsRoot} />
</section>


<style>

</style>
<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { page } from '$app/state';
	import { ctx, store } from '$lib/js/store.svelte.js';

	import BDRGMap from '$lib/cmp/district/districts/BDRGMap.svelte';
	import DistrictTree from '$lib/cmp/district/districts/DistrictTree.svelte';


	let { root } = $props(); // root district with children

</script>

<div class='flex flex-col items-center break-after-page' in:slide>
	<BDRGMap width={512}/>
</div>
<hr>
<DistrictTree {root}/>

<style>

</style>
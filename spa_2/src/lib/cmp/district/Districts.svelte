<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';

	import BDRGMap from '$lib/cmp/district/districts/BDRGMap.svelte';
	import DistrictTree from '$lib/cmp/district/districts/DistrictTree.svelte';


	let { district } = $props(); // root district with children

</script>

<!--h2 class='header text-center sticky top-0'>
	Landesverbände im BDRG Zuchtbuch
</h2-->

{#key district}

	<div class='flex flex-col items-center break-after-page' in:slide>
		<BDRGMap width={512}/>
	</div>
	<hr>
	<DistrictTree {district}/>
{/key}

<style>

</style>
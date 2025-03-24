<script>
	import { fade, fly, slide } from 'svelte/transition';
	import store, { federation } from '$lib/js/store.svelte.js';

	import BDRGMap from '$lib/cmp/district/districts/BDRGMap.svelte';
	import DistrictTree from '$lib/cmp/district/districts/DistrictTree.svelte';

	//let { federation } = $props();

	//$inspect( 'F', federation )

</script>

{#key $federation}

	<div class='flex flex-col items-center' in:slide>
		<BDRGMap width={512} root={$federation.root}/>
	</div>
	<hr>
	<DistrictTree root={$federation.root}/>
{/key}

<style>

</style>
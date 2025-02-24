<script>
	import { getContext } from 'svelte';
	import { fade, fly, slide } from 'svelte/transition';

	import BDRGMap from '$lib/cmp/district/BDRGMap.svelte';
	import DistrictTree from '$lib/cmp/district/DistrictTree.svelte';

	let federation = getContext( 'federation' );
	let page = getContext( 'page' )

	page.title = 'Die BDRG Verbände im Zuchtbuch';
	page.menu = {
		trail : [
			{ name:'Start',      href:'/' },
			{ name:'Verbände',   href:'/district' },
		],
		options : [],
	};


</script>



{#key page.title}
	<div class='flex flex-col items-center' in:slide>
		<BDRGMap width={384} root={federation.root}/>
		<hr>
		<DistrictTree root={federation.root}/>
	</div>
{/key}

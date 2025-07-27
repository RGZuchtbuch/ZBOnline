<script>
	import {page} from '$app/state';
	import { goto } from '$app/navigation';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import { addCrumb } from '$lib/js/tools.js';

	import District from '$lib/cmp/moderator/District.svelte';

	let { children } = $props();

	$effect( () => {
		const districtId = +page.params.district
		if( dirty.district || page.url ) loadDistrict( districtId );
	});

	$effect( () => {
		//if( ctx.district ) addCrumb( { name:ctx.district.short, href:page.url.href } );
	})


	function loadDistrict( id ) {
		console.log( 'Load district' );
		dirty.district = false;
		ctx.district = ctx.federation.districts[ id ];//store.federation.districts[ +page.params.district ];
	}

</script>

{@render children()}


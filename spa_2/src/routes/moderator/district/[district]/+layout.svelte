<script>

	import {page} from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	let { children } = $props();

	$effect( () => {
		const districtId = +page.params.district;
	 	setDistrict( districtId );
		if( dirty.breeders && page.url ) setBreeders( districtId );
	});

	function setDistrict( districtId ) {
		ctx.district = ctx.federation.districts[ districtId ];
	}
	async function setBreeders( districtId ) {
		ctx.breeders = await model.Breeder.query( { district:districtId } );
	}


</script>

{#if ctx.district && ctx.breeders }
	{@render children()}
{/if}


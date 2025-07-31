<script>

	import {page} from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import { addCrumb } from '$lib/js/tools.js';


	import Breeders from '$lib/cmp/moderator/district/Breeders.svelte';
	import model from '$lib/js/model.js';

	$effect( async () => {
		const districtId = +page.params.district;
		console.log( districtId );
		if( dirty.breeders || ( ctx.district && ctx.district.id !== districtId ) ) await loadBreeders( districtId );
	})

	$effect( async () => {
		if( ctx.district && ctx.breeders ) setHeader();
	})

	async function loadBreeders( districtId ) {
		console.log( 'load district breeders' );
		dirty.breeders = false;
		ctx.breeders = null;
		ctx.breeders = await model.Breeder.query( { district:districtId } );
	}

	function setHeader() {
		ctx.header = {
			title : `Züchter im ${ctx.district.name}`,
			menu  : {
				trail: [
					{name: 'Home', href: '/'},
					{name: 'Obmann', href: '/moderator'},
					{name: ctx.district.short, href: `/moderator/${ctx.district.id}`},
					{name: 'Züchter'},
				],
				options: [
					{name: 'Eingaben', href: `/moderator/${ctx.district.id}/result`},
				],
			}
		}
	}

</script>
Breeders
{#if ctx.breeders != null}
	<Breeders breeders={ctx.breeders} district={ctx.district} />
{/if}



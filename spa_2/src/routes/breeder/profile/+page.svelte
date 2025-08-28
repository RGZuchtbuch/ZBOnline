<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import Profile from '$lib/cmp/breeder/profile.svelte';
	import {addCrumb, fullName, shortName, txt} from '$lib/js/tools.js';

	console.log( 'Q', ctx.breeder, ctx.district );

	$effect( async () => {
		if( ctx.breeder ) setHeader();
	} );

	function setHeader() {
		ctx.menustate[ '/breeder' ] = page.url.href;


		ctx.title = `Züchter ${fullName(ctx.breeder)}, Mitglied`;
		ctx.submenu = [
//			{name: 'Stämme', href: `/breeder/pair`},
			//{name: 'Mitglied', href: `/breeder/profile`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: `Züchter`, href: `/breeder` },
			{name: 'Mitglied'},
		];
	}



</script>

{#if ctx.breeder && ctx.district }
	<Profile bind:breeder={ctx.breeder} district={ctx.district} readonly/>
{/if}

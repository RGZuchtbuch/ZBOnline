<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import Profile from '$lib/cmp/breeder/profile.svelte';
	import {addCrumb, fullName, shortName, txt} from '$lib/js/tools.js';

	console.log( ctx.breeder, ctx.district );

	$effect( async () => {
		if( ctx.breeder ) {
			setHeader();
			addCrumb( { name:'Mitglied', url:page.url } );
		}
	} );

	function setHeader() {
		ctx.header = {
			title: `Mitgliedsdaten für Züchter ${fullName(ctx.breeder)}`,
			menu: {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Züchter', href: `/breeder`},
					{name: `${shortName(ctx.breeder)}`, href: `/breeder/me/${ctx.breeder.id}`},
					{name: 'Mitglied'},
				],
				options: [
					{name: 'Stämme', href: `/breeder/me/${ctx.breeder.id}/pair`},
				],
			}
		}
	}



</script>

{#if ctx.breeder && ctx.district }
	<Profile breeder={ctx.breeder} district={ctx.district} />
{/if}

<script>

	import { page } from '$app/state';
	import { goto } from '$app/navigation';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import { addCrumb, fullName, shortName, txt} from '$lib/js/tools.js';
	import model from '$lib/js/model.js';
	import Pairs from '$lib/cmp/breeder/Pairs.svelte';

	// breeder loaded in layout

	$effect(async () => {
		if( ctx.district !== null && ctx.breeder !== null ) setHeader();
	})

	function setHeader() {
		console.log( 'setHeader', ctx.breeder.firstname );
		ctx.header = {
			title: ctx.breeder.id === 0 ? 'Neu' : `Zuchter ${fullName(ctx.breeder)} im ${ctx.district.name}`,
			menu: {
				trail: [
					{name: 'Home', href: '/'},
					{name: 'Obmann', href: '/moderator'},
					{name: ctx.district.short, href: `/moderator/${ctx.district.id}`},
					{name: 'Züchter', href: `/moderator/${ctx.district.id}/breeder`},
					{name: ctx.breeder.id === 0 ? 'Neu' : `${shortName(ctx.breeder)}`},
				],
				options: [
					{name: 'Stämme', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/pair`},
					{name: 'Mitglied', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/profile`},
				],
			}
		}
	}

</script>

Breeder
{#if ctx.breeder}
	Breeder info here
	<!--Pairs breeder={data.breeder} district={data.district} pairs={data.pairs} year={data.year} /-->
{/if}




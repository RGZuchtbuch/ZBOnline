<script>
	import { ctx } from '$lib/js/store.svelte.js';
	import { txt } from '$lib/js/tools.js';
	import Pairs from '$lib/cmp/breeder/Pairs.svelte';

	let { data } = $props();

	// ctx.breeder = data.breeder;
	// ctx.district = data.district;
	// ctx.pairs = data.pairs;
	// // $effect( () => {
	// 	ctx.breeder = data.breeder;
	// 	ctx.district = data.district;
	// 	ctx.pairs = data.pairs;
	// })


	$effect(async () => {
		ctx.header = {
			title :
				ctx.breeder.id===0 ?
				'Neu' :
				`Zuchter ${ctx.breeder.firstname} ${txt(ctx.breeder.infix)} ${ctx.breeder.lastname} Stämme/Paare`,
			menu : {
				trail: [
					{name: 'Home', href: '/'},
					{name: 'Obmann', href: '/moderator'},
					{name: ctx.district.short, href: `/moderator/${ctx.district.id}`},
					{name: 'Züchter', href: `/moderator/${ctx.district.id}/breeder`},
					{
						name: ctx.breeder.id === 0 ?
							'Neu' :
							`${ctx.breeder.firstname.charAt(0)}.${ctx.breeder.lastname.charAt(0)}`,
						href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}`,
					},
					{name: 'Stämme'},
				],
				options: [
					//				{name: 'Stämme', href: '/moderator/' + district.id + '/breeder/' + breeder.id + '/pair'},
					{name: 'Mitglied', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/profile`},
				],
			}
		}
	})


</script>

{#if data.breeder && data.pairs}
	<Pairs breeder={data.breeder} district={data.district} pairs={data.pairs} year={data.year} />
{/if}




<script>
	import { ctx } from '$lib/js/store.svelte.js';
	import { shortName, fullName } from '$lib/js/tools.js';
	import {onMount} from 'svelte';

	let { data } = $props();

	$effect( () => {
		ctx.breeder = data.breeder;
	});

	$effect( () => {
		ctx.header = {
			title: `Züchter ${fullName( ctx.breeder )}`,
			menu: {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Züchter', href: '/breeder'},
					{name: shortName( ctx.breeder ) },
				],
				options: [
					{ name:'Stämme',    href:`/breeder/${ctx.breeder.id}/pair` },
					{ name:'Mitglied',    href:`/breeder/${ctx.breeder.id}/profile` },
				],
			}
		}
	});

</script>

{#if ctx.breeder}
	<div class='text-xs text-center italic'>
		Breeder {ctx.breeder.firstname}

	</div>
	<div>
		Test
	</div>
{/if}

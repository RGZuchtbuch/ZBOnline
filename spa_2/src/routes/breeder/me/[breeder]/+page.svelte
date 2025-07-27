<script>
	import { ctx } from '$lib/js/store.svelte.js';
	import {shortName, fullName, addCrumb} from '$lib/js/tools.js';
	import {onMount} from 'svelte';
	import {page} from '$app/state';

	//let { data } = $props();

	console.log( 'page breeder', ctx.breeder );

	// $effect( () => {
	// 	ctx.breeder = data.breeder;
	// });

	$effect( () => {
		if( ctx.breeder ) {
			setHeader();
			addCrumb( { name:shortName(ctx.breeder), url:page.url } );
		}
	});

	function setHeader() {
		ctx.header = {
			title: `Züchter ${fullName(ctx.breeder)}`,
			menu: {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Züchter', href: '/breeder'},
					{name: shortName(ctx.breeder)},
				],
				options: [
					{name: 'Stämme', href: `/breeder/me/${ctx.breeder.id}/pair`},
					{name: 'Mitglied', href: `/breeder/me/${ctx.breeder.id}/profile`},
				],
			}
		}

	}

</script>

{#if ctx.breeder}
	<div class='text-xs text-center italic'>
		Breeder {ctx.breeder.firstname}

	</div>
	<div>
		Test
	</div>
{/if}

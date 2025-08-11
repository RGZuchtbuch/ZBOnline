<script>
	import { ctx } from '$lib/js/store.svelte.js';
	import {shortName, fullName, addCrumb} from '$lib/js/tools.js';
	import {onMount} from 'svelte';
	import {page} from '$app/state';
	import {redirect} from '@sveltejs/kit';
	import {goto} from '$app/navigation';

	$effect( () => {
		if( ctx.breeder ) setHeader();
	});

	function setHeader() {
		ctx.menustate[ '/breeder' ] = page.url.href;
		ctx.title = `Züchter ${fullName(ctx.breeder)}`;
		ctx.submenu = [
			{name: 'Stämme', href: `/breeder/pair`},
			{name: 'Mitglied', href: `/breeder/profile`},
		];
		ctx.crumbs = [
			{name: 'Start', href: '/'},
			{name: `Züchter ${shortName(ctx.breeder)}` },
		];
		// ctx.header = {
		// 	title: `Züchter ${fullName(ctx.breeder)}`,
		// 	menu: {
		// 		trail: [
		// 			{name: 'Start', href: '/'},
		// 			{name: `Züchter ${shortName(ctx.breeder)}` },
		// 		],
		// 		options: [
		// 			{name: 'Stämme', href: `/breeder/pair`},
		// 			{name: 'Mitglied', href: `/breeder/profile`},
		// 		],
		// 	}
		// }
	}

</script>

{#if ctx.breeder}
	Hier gehts zu deine Stämme oder deine Kontaktdaten
{/if}

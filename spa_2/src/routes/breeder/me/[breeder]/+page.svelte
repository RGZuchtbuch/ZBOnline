<script>
	import { ctx } from '$lib/js/store.svelte.js';
	import {shortName, fullName, addCrumb} from '$lib/js/tools.js';
	import {onMount} from 'svelte';
	import {page} from '$app/state';
	import {redirect} from '@sveltejs/kit';
	import {goto} from '$app/navigation';

	//let { data } = $props();

	console.log( 'page breeder', ctx.breeder );

	// $effect( () => {
	// 	ctx.breeder = data.breeder;
	// });
	goto( `/breeder/me/${ctx.breeder.id}/pair` );

	// $effect( () => {
	// 	if( ctx.breeder ) {
	// 		setHeader();
	// 		addCrumb( { name:shortName(ctx.breeder), url:page.url } );
	// 	}
	// });
	//
	// function setHeader() {
	// 	ctx.header = {
	// 		title: `Züchter ${fullName(ctx.breeder)}`,
	// 		menu: {
	// 			trail: [
	// 				{name: 'Start', href: '/'},
	// 				{name: 'Züchter', href: '/breeder'},
	// 				{name: shortName(ctx.breeder)},
	// 			],
	// 			options: [
	// 				{name: 'Stämme', href: `/breeder/me/${ctx.breeder.id}/pair`},
	// 				{name: 'Mitglied', href: `/breeder/me/${ctx.breeder.id}/profile`},
	// 			],
	// 		}
	// 	}
	//
	// }

</script>

{#if ctx.breeder}
	Sofort gehts zu deine Stämme....
{/if}

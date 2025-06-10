<script>

	import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import { txt } from '$lib/js/toolbox.js';
	import { Breeder } from '$lib/js/breeder.svelte.js';
	import { Pair } from '$lib/js/pair.svelte.js';

	import PairsCmp from '$lib/cmp/breeder/Pairs.svelte';



	let district   = $derived( store.federation.districts[ +page.params.district ] );
	let year       = $derived( +page.url.searchParams.get( 'year' ) || new Date().getFullYear()-1 );

	let breeder = $state( null );
	let pairs   = $state( null );

	$effect( async () => {
		const data = await Promise.all( [
			Breeder.load( page.params.breeder ),
			Pair.query( { breeder:page.params.breeder } )
		] );
		breeder = data[0];
		pairs   = data[1];
		setHeader();
	});

	function setHeader() {
		const title = `Stämme von Züchter ${breeder.firstname} ${txt( breeder.infix )} ${breeder.lastname}`;
		const menu = {
			trail: [
				{ name:'Home', href:'/' },
				{ name:'Obmann', href:'/moderator' },
				{ name: district.short,  href:`/moderator/${district.id}` },
				{ name:'Züchter', href:`/moderator/${district.id}/breeder` },
				{
					name:`${breeder.firstname.charAt(0)}.${breeder.lastname.charAt(0)}`,
					href:`/moderator/${district.id}/breeder/${breeder.id}`,
				},
				{ name:'Stämme', href:page.url.href },
			],
			options: [],
		}
		store.title = title; // to set after loading
		store.menu  = menu;
	}


	$inspect( 'BP', pairs);
	// TODO
</script>

{#if breeder && pairs && year}
	<PairsCmp {breeder} {district} {pairs} {year} />
{/if}
<script>
	import { getContext } from 'svelte';
	import { page } from '$app/state';
	import store, { federation } from '$lib/js/store.svelte.js';
	import { txt } from '$lib/js/toolbox.js';
	import api from '$lib/js/api.js';

	//let { data } = $props();
	//let state = getContext( 'state' );

	let district   = $derived( $federation.districts[ +page.params.districtId ] );
	let year       = $derived( +page.url.searchParams.get( 'year' ) || new Date().getFullYear()-1 );

	let breeder = $state( null );
	let pairs   = $state( null );

	$effect( () => {
		load( page );
	});

	async function load( page ) {
		const promises = [
			api.breeder.get( page.params.breederId ),
			api.pair.get( { breeder:page.params.breederId, year:year } )
		]
		const responses = await Promise.all( promises );

		breeder = responses[0].breeder;
		pairs   = responses[1].pairs;
		setHeader();
		console.log( 'BP', breeder, pairs )
	}

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
		store.title.update(() => title); // to set after loading
		store.menu.update(() => menu);
	}


	console.log('Pairs page');
	// TODO
</script>

{#if breeder && pairs}
	<h3 class='text-center italic'>
		Stämme {pairs.length}
	</h3>
	<ul>
		{#each pairs as pair}
			<li><a href={`${page.url.href}/${pair.id}`}>{pair.name}</a></li>
		{/each}
	</ul>
{/if}
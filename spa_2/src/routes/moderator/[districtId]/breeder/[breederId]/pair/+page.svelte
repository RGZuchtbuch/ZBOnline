<script>
	import { getContext } from 'svelte';
	import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import { txt } from '$lib/js/toolbox.js';

	let { data } = $props();
	let state = getContext( 'state' );


	state.title = `Stämme von Züchter d  ${data.breeder.firstname} ${txt( data.breeder.infix )} ${data.breeder.lastname}`;
	state.menu = {
		trail : [
			{ name:'Home',              href:'/' },
			{ name:'Obmann',            href:'/moderator' },
			{ name: data.district.short,  href:'/moderator/'+data.district.id },
			{ name:'Zuechter',          href:'/moderator/'+data.district.id+'/breeder' },
			{ name:`${data.breeder.firstname.charAt(0)}.${data.breeder.lastname.charAt(0)}`, href:'/moderator/'+data.district.id+'/breeder/'+data.breeder.id },
			{ name:'Stamm',             href:data.url.pathname },
		],
		options : [],
	};


	console.log('Pairs page');
	// TODO
</script>

{#if data.district && data.breeder && data.pairs}
	<h3 class='text-center italic'>
		Stämme {data.pairs.length}
	</h3>
	<ul>
		{#each data.pairs as pair}
			<li><a href={`/moderator/${data.district.id}/breeder/${data.breeder.id}/pair/${pair.id}`}>{pair.name}</a></li>
		{/each}
	</ul>
{/if}
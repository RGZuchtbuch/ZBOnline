<script>
	import { cache, state } from '$lib/js/store.svelte.js';
	import {onMount} from 'svelte';

	let { data } = $props();


	onMount( () => {
		state.title = 'Stamm';
		state.trail = [
			{ name:'Home',   href:'/' },
			{ name:'Stamm',  href:`/pair/${data.pairId}` },
		];
		state.options = [
			{ name:'Karte', href:'/district/map' },
			{ name:'Liste', href:'/district/list' },
		];
	})
	console.log( 'Articles H', history );
</script>

<p>
	Stamm {data.pair.id}
</p>
<hr>

<ul>
	Stamm form
</ul>
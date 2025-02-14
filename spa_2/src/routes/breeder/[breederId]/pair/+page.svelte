<script>
	import { cache, state } from '$lib/js/store.svelte.js';
	import {onMount} from 'svelte';

	let { data } = $props();

	onMount( () => {
		state.title = `Züchter ${data.breeder.firstname} ${data.breeder.infix} ${data.breeder.lastname}`;
		state.trail = [
			{ name:'Home',      href:'/' },
			{ name:'Züchter',   href:`/breeder/${data.breeder.id}` },
			{ name:'Stämme',    href:`/breeder/${data.breeder.id}/pair` },
		];
		state.options = [
			{ name:'Verband', href:`/district/${data.breeder.districtId}` },
		]
	})

</script>

<div class='text-xs text-center italic'>
	Stämme für {data.breeder.firstname}
</div>
<ul>
	{#each data.pairs as pair}
		<li><a href=''>{pair.id}</a></li>
	{/each}
</ul>

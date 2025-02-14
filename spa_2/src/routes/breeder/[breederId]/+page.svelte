<script>
	import { cache, state } from '$lib/js/store.svelte.js';
	import {onMount} from 'svelte';

	let { data } = $props();

	onMount( () => {
		state.title = `Züchter ${data.breeder.firstname} ${data.breeder.infix} ${data.breeder.lastname}`;
		state.trail = [
			{ name:'Home',      href:'/' },
			{ name:'Züchter',   href:`/breeder/${data.breeder.id}` },
		];
		state.options = [
			{ name:'Stämme',    href:`/breeder/${data.breeder.id}/pair` },
			{ name:'Mitglied',    href:`/breeder/${data.breeder.id}/profile` },
			{ name:'Verband',    href:`/district/${data.breeder.districtId}/breeder` },

		];
	})

</script>

<div class='text-xs text-center italic'>
	Breeder {data.breeder.firstname} ddfff

</div>
<div>
	Test
</div>

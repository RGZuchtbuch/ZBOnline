<script>
	import { page } from '$app/stores';
	import Pairs from '../pair/Pairs.svelte';

	export let breeder;
	export let pairs;

	console.log( 'BP', breeder, pairs)
</script>


<h1>Stämme</h1>

<Pairs {pairs}/>
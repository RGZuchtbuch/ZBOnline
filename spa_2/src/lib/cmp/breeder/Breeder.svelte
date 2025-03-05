<script>
	import Pairs from '../pair/Pairs.svelte';

	let { data } = $props();


	console.log( 'BP', data.breeder, data.pairs)
</script>


<h1>Stämme</h1>

<Pairs pairs={data.pairs}/>
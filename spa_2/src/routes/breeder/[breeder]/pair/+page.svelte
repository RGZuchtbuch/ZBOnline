<script>
	import { ctx } from '$lib/js/store.svelte.js';
	import {onMount} from 'svelte';
	import {fullName, shortName} from '$lib/js/tools.js';

	let { data } = $props();

	$effect( () => {
		ctx.header = {
			title : `Züchter ${fullName( ctx.breeder )}`,
			menu: {
				trail : [
					{name: 'Start', href: '/'},
					{name: 'Züchter', href: '/breeder'},
					{name: shortName( ctx.breeder ), href:`/breeder/${data.breeder.id}` },
					{name:'Stämme' },
				],
				options : [
				]
			}
		};

	} )

</script>

<div class='text-xs text-center italic'>
	Stämme für {data.breeder.firstname}
</div>
<ul>
	{#each data.pairs as pair}
		<li><a href=''>{pair.id}</a></li>
	{/each}
</ul>

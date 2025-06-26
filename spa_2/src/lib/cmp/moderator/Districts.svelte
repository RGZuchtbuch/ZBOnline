<script>
	import { slide } from 'svelte/transition';
	import store from '$lib/js/store.svelte.js';
	import {goto} from '$app/navigation';
	//import { getContext } from 'svelte';

	let { districts } = $props();
	let year = new Date().getFullYear()-1;

</script>

<section>
	<h3 class='header'>Verbände zum Verwalten</h3>
	{#if districts}
		<ol in:slide>
			{#each districts as district, i}
				<li class='flex flex-row items-center'>
					<a href={`/moderator/${district.id}/result/${year}`} title='Verband verwalten'>
						<div class='w-6 text-right '>{i+1}.</div>
						<div>{district.name}</div>
					</a>
				</li>
			{/each}
		</ol>
	{/if}
</section>

<style>
    section {
    }
    .header {
        @apply w-full bg-teal-200 rounded-t text-center
    }

    a {
        @apply w-full flex flex-row border-b p-2 gap-x-2;
    }

    ol {
	    @apply p-6;
    }

</style>
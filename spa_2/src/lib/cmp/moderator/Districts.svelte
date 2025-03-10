<script>
	import store, { districts, federation, user } from '$lib/js/store.svelte.js';
	import {goto} from '$app/navigation';
	//import { getContext } from 'svelte';

	//let { districts } = $props();

	function getDistricts() {
		let modDistricts = [];
		$user.moderator.forEach( id => {
			modDistricts.push( $federation.districts[id] );
		});
		districts.update( () => modDistricts );
	}



	if( $user.moderator.length === 1 ) { // if only one option go there
		goto( `/moderator/${$user.moderator[0]}`);
	} else {
		getDistricts();
	}

	console.log( districts.length );
</script>

<section>
	<h3 class='header'>Verbände zum Verwalten</h3>
	{#if $districts}
		<ol in:slide>
			{#each $districts as district, i}
				<li class='flex flex-row items-center'>
					<a href={`/moderator/${district.id}`} title='Wählen'>
						<div class='w-16 text-right '>{i+1}.</div>
						<div>{district.name}</div>
					</a>
				</li>
			{/each}
		</ol>
	{/if}
</section>

<style>
    section {
        @apply border m-4;
    }
    .header {
        @apply w-full bg-teal-200 rounded-t text-center
    }

    a {
        @apply flex flex-row border-b p-2 gap-x-2;
    }

</style>
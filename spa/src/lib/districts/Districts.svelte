<script>
	import { meta } from 'tinro';
	import api from '../../js/api.js';
	import { txt } from '../../js/util.js';

	import List from "../common/List.svelte";
	import Tree from '../common/Tree.svelte';

	let district = null;
	const route = meta();


	function loadDistricts() {
		api.district.descendants.get( 1 ).then( response => {
			district = response.district;
		} );
	}



	loadDistricts(); // if user changes by logout/login or exp
</script>


<List>
	<div slot='header' >Verbände und Unterverbände im BDRG Zuchtbuch </div>
	<div slot='body'>
		{#if district}
			<Tree node={district} open={true} let:node={district}>
				<div class='flex flex-row grow'>
					<div class='grow'>
						<span class=''>{district.name}</span>
						<small class='w-8 text-center' title='Zahl der Unterverbände'> [{district.children.length}]</small>
					</div>
					<div class='w-64 flex'>
						{#if district.moderator}
							<img class='px-2 fill-green-600' src='assets/obmann.svg' alt='link'>
							{txt(district.moderator.firstname)} {txt(district.moderator.infix)} {txt(district.moderator.lastname)}
						{/if}
					</div>
					{#if district.url}
						<a class='w-4 cursor-pointer' href={district.url} target='_blank'> <img src='assets/link.svg' alt='link'> </a>
					{:else}
						<div class='w-4'></div>
					{/if}
					<small class='w-6 text-gray-400 text-3xs text-right cursor-auto' title='item id'>[{district.id}]</small>
				</div>
			</Tree>
		{/if}
	</div>
</List>

<style>
</style>
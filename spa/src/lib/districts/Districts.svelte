<script>

	import { meta } from 'tinro';
	import api from '../../js/api.js';
	import { txt } from '../../js/util.js';
	import dic from '../../js/dictionairy.js';

	import Page from "../common/Page.svelte";
	import Tree from '../common/Tree.svelte';



	let districts = null;
	const route = meta();


	function loadDistricts() {
		api.district.descendants.get( 1 ).then( response => {
			districts = response.district;
		} );
	}



	loadDistricts(); // if user changes by logout/login or exp
</script>


<Page>
	<div slot='title' >Verbände im BDRG Zuchtbuch </div>
	<div slot='body'>
		{#if districts}
			<Tree node={districts} open={true} let:node={districts}>
				<div class='flex flex-col md:flex-row grow'>
					<div class='grow' title={districts.id}>
						{districts.name}
					</div>
					<div class='flex flex-row gap-x-2 justify-between'>
						{#if districts.moderator}
							<img class='w-4 fill-green-600' src='assets/obmann.svg' alt='link'>
							<div class='w-48'>
								{txt(districts.moderator.firstname)} {txt(districts.moderator.infix)} {txt(districts.moderator.lastname)}
							</div>

							{#if districts.moderator.email}
								<a class='w-8 cursor-pointer' href={'/kontakt/'+districts.id} title='eMail am Obmann'>&#9993;</a>
							{:else}
								<div class='w-8'></div>
							{/if}

						{/if}

						{#if districts.url}
							<a class='w-8 cursor-pointer' href={districts.url} target='_blank' title={dic.title.url}>
								<img src='assets/link.svg' alt='link'>
							</a>
						{:else}
							<div class='w-8'></div>
						{/if}
					</div>
				</div>
			</Tree>
		{/if}
	</div>
</Page>

<style>
</style>
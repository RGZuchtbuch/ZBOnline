<script>
	import Form, { NumberInput, Select } from '$lib/cmp/form/Form.svelte';
	import { fullName } from '$lib/js/tools.js';

	let breederId = null;

	let breeders = $state( [
		{ id:1, firstname:'Eelco', infix:null, lastname:'Jannink', results: [ {id:1} ] },
		{ id:2, firstname:'Jan', infix:null, lastname:'Bartels', results: [ {id:2} ] }
	] );



	function addResult( breeder ) {
		return ( event ) => {
			breeder.results.push( {id: 3} );
		}
	}
</script>

<main class='p-4'>
	<section>

		{#each breeders as breeder}
			<details open={ true }>
				<summary class='py-1 flex flex-row justify-between'><span>{fullName( breeder ) }</span> <button type='button' onclick={addResult( breeder )}> [ + ]</button></summary>
				{#each breeder.results as result}
					Take layout from verband gesamt
					<Form class='px-4 flex flex-row gap-x-2'>
						<Select class='w-24' label='Sparte' value='1' ><option>A</option></Select>
						<Select class='w-48' label='Rasse' value='1' ><option>A</option></Select>
						<Select class='w-48' label='Farbe' value='1' ><option>A</option></Select>

						<span class='w-4'></span>
						<NumberInput class='w-12' label='Eier/J' value='1' />
						<NumberInput class='w-12' label='Gewicht' value='1' />
						<span class='w-4'></span>
						<NumberInput class='w-12' label='Eingelegt' value='1' />
						<NumberInput class='w-12' label='Befruchtet' value='1' />
						<NumberInput class='w-12' label='Geschlüpft' value='1' />
						<span class='w-4'></span>
						<NumberInput class='w-12' label='Tiere' value='1' />
						<NumberInput class='w-12' label='Note' value='1' />
					</Form>
				{/each}
			</details>
		{/each}
a		<Select value={breederId} >
			{#each breeders as breeder}
				<option value={breeder} >{fullName( breeder ) }</option>
	        {/each}
		</Select>
	</section>


</main>

List of breeders/breed/color results, add/edit breed / color in placee
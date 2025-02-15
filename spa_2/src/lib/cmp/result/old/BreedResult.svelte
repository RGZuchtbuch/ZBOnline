<script>
	import {fade, slide} from 'svelte/transition';
	import validator from '../../form/validator.js';
	import Form from '../../form/Form.svelte';
	import Number from '../../form/input/Number.svelte';
	import Text from '../../form/input/Text.svelte';
	import Status from '../../form/Status.svelte';
	import Submit from '../../form/Submit.svelte';
	import Result from './DistrictResult.svelte';
	import {onMount} from 'svelte';
	import DistrictResult from './DistrictResult.svelte';
	import PairResult from './PairResult.svelte';

	export let results;

	let total = {
		breeders:null, breederNr:null, pairs:null,
		lay:{ eggs:null, weight:null },
		brood: { eggs:null, fertile:null, hatched:null },
		show: { count:null, score:null },
	};

	let showColors = false;
	let showBreeders = true;

	function onClick( event ) {
		console.log( 'add' );
		results.push( {
			breeders:0, pairs:0,
			lay:{ eggs:null, weight:null },
			brood: { eggs:null, fertile:null, hatched:null },
			show: { count:null, score:null },
		});
		results = results; // triggger
	}

	function onToggleColors( event ) {
		showColors = ! showColors;
	}
	function onToggleBreeders( event ) {
		showBreeders = ! showBreeders;
	}

	function onResultChange() {
		calcTotal()
	}

	function calcTotal( event ) {
		total =  {
			breeders:results.district.breeders, pairs:results.district.pairs,
			lay:{ eggs:results.district.eggs, weight:results.district.weight },
			brood: { eggs:results.district.eggs, fertile:results.district.fertile, hatched:results.district.hatched },
			show: { count:results.district.count, score:results.district.score },
		};
		total.breeders += unique( results.pairs, ( result ) => result.breeder );
		for (const result of results.pairs) {
			total.pairs += 1;
			total.brood.eggs    += result.brood.eggs;
			total.brood.fertile += result.brood.fertile;
			total.brood.hatched += result.brood.hatched;
			total.show.count    += result.show.count;
		}
		total.lay.eggs   = avg( results, ( result ) => result.lay.eggs );
		total.lay.weight = avg( results, ( result ) => result.lay.weight );
		total.show.score = avg( results, ( result ) => result.show.score );
	}

	function unique( items, selector ) {
		const found = {};
		console.log( 'Found', found, found.length);
		for( const item of items ) {
			const value = selector( item );
			found[value] = value;
		}
		console.log( 'Found', found, found.length);
		return Object.keys( found ).length;
	}

	function avg( items, selector ) {
		if( items.length > 0 ) {
			let count = 0;
			let sum = 0;

			for( const item of items ) {
				let value = selector( item );
				if( value !== null ) {
					count += 1;
					sum += value;
				}
			}
			return count > 0 ? sum/count : null;
		}
		return null;
	}
	onMount( () => {
		calcTotal();
	});

</script>

<div class='w-128' on:click={onToggleColors}>Altenglische Zwerg-Kämpfer</div>
{#if showColors}
	<ColorResults />

	<div class='flex flex-row items-center' in:fade>
		<Form class='flex flex-row items-center gap-x-2' disabled>
			<div class='w-80 pl-2 align-baseline italic' on:click={onToggleBreeders}>⤷ blau-silberhalsig m.Orangerücken</div>
			<Number class='w-12' label='Züchter' value={total.breeders}/>
			<div class='w-2'/>
			<Number class='w-12' label='Stämme' value={total.pairs }/>
			<div class='w-2'/>
			<Number class='w-12' label='Eier/J' value={total.lay.eggs} />
			<Number class='w-12' label='Gewicht' value={total.lay.weight} />
			<div class='w-2'/>
			<Number class='w-12' label='Eingelegt' value={total.brood.eggs}/>
			<Number class='w-12' label='Befruchtet' value={total.brood.fertile} />
			<Number class='w-12' label='Geschlüpft' value={total.brood.hatched} />
			<div class='w-2'/>
			<Number class='w-12' label='Tiere' value={total.show.count} />
			<Number class='w-12' label='Punkte' value={total.show.score} />
		</Form>
	</div>

	{#if showBreeders}
		<div transition:slide>
			<DistrictResult result={results.district} on:change={onResultChange}/> <!-- always there -->
			{#each results.pairs as result, i }
				<PairResult {result} labeled={i===0} on:change={onResultChange} disabled /> <!-- from breeder pairs -->
			{/each}
		</div>
	{/if}
	<div>jhgjkhgkjhgkasgdjahdkjhagskjhgaskjhg</div>
{/if}

<style></style>
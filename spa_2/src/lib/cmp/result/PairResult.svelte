<script>
	import {fade} from 'svelte/transition';
	import validator from '../../form/validator.js';
	import Form from '../../form/Form.svelte';
	import Number from '../../form/input/Number.svelte';
	import Text from '../../form/input/Text.svelte';
	import Status from '../../form/Status.svelte';

	export let disabled = false;
	export let result;
	export let labeled = false;

	const validate = {
		breeder   : (v) => validator(v).number().range( 1, 99999).orNull().isValid(),
		pairs       : (v) => validator(v).number().range( 1, 999 ).orNull().isValid(),
		lay     : {
			eggs    : (v) => validator(v).number().range( 0, 399 ).orNull().isValid(),
			weight  : (v) => validator(v).number().range( 1, 9999 ).orNull().isValid(),
		},
		brood   : {
			eggs    : (v) => validator(v).number().range( 0, 399 ).orNull().isValid(),
			fertile : (v) => validator(v).number().range( 0, result.brood.eggs ).orNull().isValid(),
			hatched : (v) => validator(v).number().range( 0, result.brood.fertile ? result.brood.fertile : result.brood.eggs).orNull().isValid(),
		},
		show    : {
			count   : (v) => validator(v).number().range(0, 999).orNull().isValid(),
			score   : (v) => validator(v).number().range(89, 97).orNull().isValid(),
		},
	}

	const help = {
		lay: { start:null, end:null, hens:null, eggs:null }
	}

	console.log( 'Result', result, labeled);
</script>

<div class='flex flex-row'>

	<Form class='flex flex-row gap-x-2' on:change {disabled}>
		<output class='w-80 pr-4 text-right content-end pb-3'>Eelco Jannink</output>
		<Text class='w-12' label={labeled?'ZüchterNr' : null} bind:value={result.breeder} validator={validate.breeder}/>
		<div class='w-2'/>
		<Text class='w-12' label={labeled?'StammNr' : null} bind:value={result.pairs} validator={validate.pair} />
		<div class='w-2'/>
		<Number class='w-12' label={labeled?'Eier/J' : null} bind:value={result.lay.eggs} validator={validate.lay.eggs} />
		<Number class='w-12' label={labeled?'Gewicht' : null} bind:value={result.lay.weight} validator={validate.lay.weight} />
		<div class='w-2'/>
		<Number class='w-12' label={labeled?'Eigelegt' : null} bind:value={result.brood.eggs} validator={validate.brood.eggs} />
		<Number class='w-12' label={labeled?'Befruchtet' : null} bind:value={result.brood.fertile} validator={validate.brood.fertile} />
		<Number class='w-12' label={labeled?'Geschlüpft' : null} bind:value={result.brood.hatched} validator={validate.brood.hatched} />
		<div class='w-2'/>
		<Number class='w-12' label={labeled?'Tiere' : null} bind:value={result.show.count} validator={validate.show.count} />
		<Number class='w-12' label={labeled?'Punkte' : null} bind:value={result.show.score} validator={validate.show.score} />
	</Form>
</div>


<style></style>
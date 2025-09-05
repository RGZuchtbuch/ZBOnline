<script>
	import {goto, invalidate} from '$app/navigation';
	import { ctx } from '$lib/js/store.svelte.js';
	import { fullName } from '$lib/js/tools.js';
	import model from '$lib/js/model.js';
	import Form, { CheckBox, DateInput, EmailInput, NumberInput, TextInput, Status, Submit, validator } from '$lib/cmp/form/Form.svelte';
	import TextArea from '$lib/cmp/form/input/TextArea.svelte';

	let { to } = $props();

	let message = $state( { to:to, email:ctx.user ? ctx.user.email : null, name:ctx.user ? fullName( ctx.user ) : null, subject:null, message:null });
	let disabled = $state( false );
	// submit button text
	const values = { initial:null, waiting:'Moment...', changed:'Controlle', invalid:'Verschicken geht noch nicht', valid:'Verschicken', disabled:'Geht nicht', stored:'Bericht ist verschickt', error:'Oops, Server Fehler :(' };

	const validate = {
		email:      v => validator(v).email().isValid(),
		name:       v => validator(v).string().length( 2, 128 ).isValid(),
		subject:    v => validator(v).string().length( 2, 64 ).isValid(),
		message:    v => validator(v).string().length( 2, 65535).isValid(),
	}
	//    name:       v => validator(v).string().orNullIf( pair.delete ).isValid(),

	async function onSubmit() {
		// invalidate( 'breeder' );
		// if( breeder.start && breeder.firstname && breeder.lastname && breeder.club ) {
		// 	return await model.Breeder.save( breeder );
		// } else if( breeder.id > 0 && breeder.firstname === null && breeder.lastname === null && breeder.delete ) {
		// 	console.log( 'Delete', breeder.firstname, 'TODO' );
		// }
		let success = await model.Message.save( message );
		if( success ) {
			disabled = true;
			setTimeout( () => {
				history.back();
			}, 2500 );
			return true;
		}
		return false;
	}

	$effect( () => {
	})
</script>

{#if true }
	<Form class='flex flex-col p-4 gap-y-4' autosubmit={false} onsubmit={onSubmit} {disabled}>
		<div class='text-right'><Status /></div>

		<fieldset class='flex flex-col gap-x-2 p-4'>
			<legend>Bericht schicken</legend>
			<TextInput class='w-128' label='Ihr Name' bind:value={message.name} error='Pflichtfeld' validator={validate.name}/>
			<EmailInput class='w-128' label='Ihre EMailadresse' bind:value={message.email} error='Pflichtfeld' validator={validate.email}/>
			<div class='h-8' ></div>
			<TextInput class='' label='Betrifft' bind:value={message.subject} error='Pflichtfeld' validator={validate.subject}/>
			<TextArea class='h-64' label='Nachricht' bind:value={message.message} error='Pflichtfeld' validator={validate.message}/>
			<Submit {values}/>
		</fieldset>
	</Form>
{/if}
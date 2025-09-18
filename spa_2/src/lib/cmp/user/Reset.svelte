<script>
    import {getContext, onMount} from 'svelte';
    import { goto } from '$app/navigation';

    import { ctx } from '$lib/js/store.svelte.js';
    import { txt } from '$lib/js/tools.js';
    import model from '$lib/js/model.js';

    import Form, { validator, CheckBox, DateInput, EmailInput, NumberInput, PasswordInput, RangeInput, RingInput, Status, TextInput } from '$lib/cmp/form/Form.svelte';
    import Submit from '$lib/cmp/form/Submit.svelte';
    import {jwtDecode} from 'jwt-decode';

    //const State = { LOGIN:10, LOGGEDIN:11, FAILED:12, FORGOT:20, FORGOTTEN:21, LOGOUT:30, LOGGEDOUT:31}

    //let state = $state( ctx.user ? State.LOGOUT : State.LOGIN );

    let { token } = $props();

    let decoded = $state( null );
    let user = $state( null );
    let email = $state( null );
    let password = $state( null );

    try {
        if (token) {
            decoded = jwtDecode( token );
            if( decoded ) {
                user = decoded.user;
                if( user ) {
                    email = user.email;
                }
            }
            console.log( 'Reset email from token', email );
        }
    } catch ( error ) {
        console.error( error );
    }

    const values = { initial:null, waiting:'Warten..', changed:'Controlle', invalid:'Fehler', valid:'Ok', disabled:'Geht nicht', stored:'Abmelden', error:'Server Fehler :(' };


    const validate = {
        email:      v => validator(v).email().isValid(),
        password:   v => validator(v).password().isValid(),
    }

    async function onSubmit( event ) {
        console.log( 'Submit' );
        const response = await model.User.reset( token, password );
        console.log( 'Response', response)
        await goto( '/' );
    }

</script>

{#if decoded && email}
    <div class='flex flex-col py-4 gap-2 items-center'>
        <h3>Geben Sie hier Ihr neues Passwort ein für</h3>
        <h3> {email} </h3>
        <ul>
            Ein Passwort braucht wenigstens 8 Zeichen mit
            <li> Kleine [a..z] Buchstaben</li>
            <li> Große [A..Z] Buchstaben</li>
            <li> Ziffern [0..9]</li>
            <li> Sonderzeichen [!@#$%^&*()-] !</li>
        </ul>

        <Form class='w-96 flex flex-col gap-2' validateafter={500} onsubmit={onSubmit} >
            <EmailInput class='w-96' name='email' label='eMail adresse' value={email} disabled />
            <PasswordInput class='w-96' name='password' label='Passwort' bind:value={password} error='Passwort nich volständig' validator={validate.password} />
            <Submit class='w-96' values={values} />
        </Form>

    </div>
{/if}

<style>
    button.forgot {
        @apply bg-inherit text-red-600;
    }
</style>
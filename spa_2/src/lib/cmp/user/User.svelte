<script>
    import {getContext, onMount} from 'svelte';
    import { goto } from '$app/navigation';

    import { ctx } from '$lib/js/store.svelte.js';
    import { txt } from '$lib/js/tools.js';
    import model from '$lib/js/model.js';

    import Form, { validator, CheckBox, DateInput, EmailInput, NumberInput, PasswordInput, RangeInput, RingInput, Status, TextInput } from '$lib/cmp/form/Form.svelte';
    import Submit from '$lib/cmp/form/Submit.svelte';

    const State = { LOGIN:10, LOGGEDIN:11, FAILED:12, FORGOT:20, FORGOTTEN:21, LOGOUT:30, LOGGEDOUT:31}

    let state = $state( ctx.user ? State.LOGOUT : State.LOGIN );

    let email    = $state( null );
    let password = $state( null );
    let forgot   = $state( false );
    let disabled = $state( false ); //?

    const values = { initial:null, waiting:'Warten..', changed:'Controlle', invalid:'Fehler', valid:'Ok', disabled:'Geht nicht', stored:'Abmelden', error:'Server Fehler :(' };


    const validate = {
        //born:      v => validator(v).date().orNull().isValid(),
        //age:      v => validator(v).number().range( 1, 10 ).orNull().isValid(),
        email:      v => validator(v).email().isValid(),
        password:   v => validator(v).password().isValid(),
        logout:     v => validator(v).string().isValid(),
    }

    async function onLogin( event ) {
        console.log('Logging in', email );
        let success = await model.User.login( email, password );
        if( success ) {
            state = State.LOGGEDIN;
            await goto( '/' ); // home for now
        } else {
            state = State.FAILED;
            password = null;
            disabled = false;// TODO
        }
    }

    async function onForgot( event ) {
        console.log('Send Reset', email);
        model.User.forgot( email );
        state = State.FORGOTTEN;
    }

    async function onLogout( event ) {
        state = State.LOGGEDOUT;
        await model.User.logout();
        await goto( '/' ); // home for now
    }

</script>


<div class='flex flex-col py-4 gap-2 items-center'>
    {#if state === State.LOGIN || state === State.FAILED }
        {#if state === State.LOGIN}
            <h3>Sie sind noch nicht angemeldet !</h3>
        {:else}
            <h3>Das hat nicht geklappt, versuche es nochmals !</h3>
        {/if}
        <Form class='w-96 flex flex-col gap-2' validateafter={500} submitafter={1000} onsubmit={onLogin} {disabled}>
            <EmailInput class='w-96' name='email' label='eMail adresse' bind:value={email} error='Emailadresse ungütig' validator={validate.email} autocomplete='username'/>
            <PasswordInput class='w-96' name='password' label='Passwort' bind:value={password} error='Passwort nich volständig' validator={validate.password} />
            <button class='w-96 block text-right forgot' type='button' onclick={()=>state=State.FORGOT}>Passwort vergessen</button>
            <Submit class='w-96' values={values} />
        </Form>
    {:else if state === State.LOGGEDIN }
        <h3>Wunderbar, du bist drin :)</h3>
    {:else if state === State.FORGOT }
        <h3>Sie wissen ihr Passwort nicht oder nicht Mehr ?</h3>
        <p>
            Wenn man vom Obmann registriert ist kann man einen Resetlink über eMail beantragen.
        </p>
        <Form class='w-96 flex flex-col gap-2' initialState='valid' submitafter={1000} onsubmit={onForgot} {disabled}>
            <EmailInput class='w-96' name='email' label='Ihre eMail adresse' bind:value={email} error='Emailadresse ungütig' validator={validate.email} autocomplete='username'/>
            <Submit class='w-96' values={ values} />
        </Form>
    {:else if state === State.FORGOTTEN }
        <h3>Wunderbar, du bekommst eine email mit Resetlink :)</h3>
    {:else if state === State.LOGOUT }
        <div>Züchter {ctx.user.firstname} {ctx.user.infix} {ctx.user.lastname}</div>
        <div>Abmelden vom RGZuchtbuch</div>
        <Form initialState='valid' onsubmit={onLogout} {disabled} >
            <Submit class='w-96' values={ values} />
        </Form>
    {:else if state === State.LOGGEDOUT }
        <h3>Wunderbar, du bist raus :)</h3>
    {/if}
</div>

<style>
    button.forgot {
        @apply bg-inherit text-red-600;
    }
</style>
<script>
    import { active, meta, router, Route } from 'tinro';
    import api from '../scripts/api.js';
    import { user } from '../scripts/store.js'

    //import HelperText from '@smui/textfield/helper-text';
    import IconButton from '@smui/icon-button';
    import Textfield from '@smui/textfield';

    import Box from './Box.svelte';

    let credentials = {
        email: 'eelco.jannink@gmail.com', password: 'eelco' // TODO
    }

    const route = meta();

    function submit( event ) {
        event.preventDefault();
        api.getToken( credentials.email, credentials.password ).then( data => {
            console.log('Login data', data.user );
            user.set( data.user );
            console.log( 'Stored user', $user );
        }).catch( e => {
            console.log('Error');
        } );
        console.log( 'Submit' );
    }

    console.log( 'Login' );
</script>

<Box legend='Anmelden beim Zuchtbuch Online'>
    <form>
        <div class='flex flex-col'>
            <Textfield bind:value={credentials.email} label='eMail'/>
            <Textfield type='password' bind:value={credentials.password} label='Passwort'/>
            <IconButton class='material-icons self-end' on:click={submit} title='Komm ist dein Zuchtbuch'>login</IconButton>
        </div>
    </form>
</Box>

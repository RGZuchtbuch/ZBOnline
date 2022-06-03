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

    let message = '';

    const route = meta();

    function submit( event ) {
        event.preventDefault();
        api.getToken( credentials.email, credentials.password )
            .then( data => {
                console.log( 'Success', data );
                user.set(data.user);
                message = 'Wilkommen';
                history.back();
            } )
            .catch( error =>{
                console.log('Error', error );
                message = 'Anmelding Fehlgeschlagen !';
                user.set( null );
            });
    }

    console.log( 'Login' );
</script>

<Box legend='Anmelden beim Zuchtbuch Online'>
    <form>
        <div class='flex flex-col'>
            <Textfield bind:value={credentials.email} label='eMail'/>
            <Textfield type='password' bind:value={credentials.password} label='Passwort'/>
            <div class='flex flex-row justify-between items-center'>
                <span>{message}</span>
                <IconButton class='material-icons self-end' on:click={submit} title='Komm herein'>login</IconButton>
            </div>
        </div>
    </form>
</Box>

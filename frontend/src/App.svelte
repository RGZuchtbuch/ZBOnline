<script>
    //  import logo from './assets/svelte.png'
    import {Route, meta} from 'tinro';
    import api from './js/api.js';
    import NavigationBar from './lib/NavigationBar.svelte';
    import Menu from './lib/Menu.svelte';
    import Home from './lib/Home.svelte';
    import Guest from './lib/guest/Guest.svelte';
    import Moderator from './lib/moderator/Moderator.svelte';

    import Login from './lib/Login.svelte';
    import Logout from './lib/Logout.svelte';

    import { user } from './js/store.js'

    //const route = meta();

</script>

<div class='w-full h-full flex flex-col'>
    <img class='absolute w-20 m-2 ml-8 no-print' src='../assets/bdrg_logo_r.png'>

    <div class='text-center no-print'>
        Das Rassegeflügel Zuchtbuch. Schönheit und Leisting kombinieren.
    </div>

    <NavigationBar />

    <div class='grow p-2 flex flex-row gap-2 relative min-h-0 '>

        <Menu />

        <div class='grow flex flex-col border rounded bg-white p-4 print'>

            <Route path='/' ><Home /></Route>
            <Route path='/leistungen/*' > <Guest /> </Route>
            <Route path='/obmann/*' > <Moderator /> </Route>

            <Route path='/anmelden'>
                <Login />
            </Route>
            <Route path='/abmelden' ><Logout /></Route>


        </div>

        <div class='w-48 border rounded flex flex-col no-print'>
            <h2>Info</h2>
            <div>
                {#if $user}
                    {$user.name}
                {/if}
            </div>
        </div>
    </div>

</div>

<style>
    :root {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen,
        Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

</style>

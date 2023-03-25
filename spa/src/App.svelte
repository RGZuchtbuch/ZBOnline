<script>
    //  import logo from './assets/svelte.png'
    import {Route} from 'tinro';

    import api from './js/api.js';
    import { standard, user } from './js/store.js'

    import Article from './lib/Article.svelte';
    import NavigationBar from './lib/NavigationBar.svelte';
    import Menu from './lib/Menu.svelte';
    import Home from './lib/Home.svelte';
    import Admin from './lib/admin/Admin.svelte';
    import Moderator from './lib/moderator/Moderator.svelte';
    import Results from './lib/results/Results.svelte';
    import Standard from './lib/standard/Standard.svelte';

    import Login from './lib/Login.svelte';
    import Reset from './lib/Reset.svelte';
    import Logout from './lib/Logout.svelte';



//    api.standard.get().then( response => {
//        standard.set( response.section );
//        console.log( response.section );
//    })

</script>

<div class='w-full h-full flex flex-col'>
    <img class='absolute w-20 m-2 ml-8 no-print' src='../assets/bdrg_logo_r.png' alt='BDRG Logo'>

    <div class='text-center no-print'>
        Das Rassegeflügel Zuchtbuch. Schönheit und Leisting kombinieren.
    </div>

    <NavigationBar />

    <div class='grow p-2 flex flex-row gap-2 relative min-h-0'>

        <Menu />

        <div class='grow flex flex-col border rounded bg-white items-center p-4 print'>

            <Route path='/' ><Home /></Route>
            <Route path='/seite/:articleId' let:meta> <Article id={meta.params.articleId} /> </Route>
            <Route path='/standard/*' > <Standard /> </Route>
            <Route path='/leistungen/*' > <Results /> </Route>
            <Route path='/obmann/*' > <Moderator /> </Route>

            <Route path='/admin/*' > <Admin /> </Route>

            <Route path='/anmelden'> <Login /> </Route>
            <Route path='/reset'> <Reset /> </Route>
            <Route path='/abmelden'> <Logout /></Route>


        </div>

        <div class='w-48 border rounded flex flex-col no-print'>
            <h2>Info</h2>
            <div>
                {#if $user}
                    {#if $user.admin} <div>Administrator</div>{/if}
                    {#if $user.moderator.length>0} <div>Obmann</div>{/if}
                    <div>{$user.fullname}</div>
                    <div>Bis { new Date( $user.exp * 1000 ).toLocaleString( 'de') }</div>
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

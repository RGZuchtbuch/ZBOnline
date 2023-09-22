<script>
    //  import logo from './assets/svelte.png'
    import {Route} from 'tinro';

    import api from './js/api.js';
    import { standard, user } from './js/store.js'

    import Article from './lib/article/Article.svelte';
    import NavigationBar from './lib/navigation/NavigationBar.svelte';
    import Menu from './lib/menu/Menu.svelte';
    import Home from './lib/info/Info.svelte';
    import Admin from './lib/admin/Admin.svelte';
    import Moderator from './lib/moderator/Moderator.svelte';
    import Results from './lib/result/Results.svelte';
    import Standard from './lib/standard/Standard.svelte';

    import Login from './lib/login/Login.svelte';
    import Reset from './lib/login/Reset.svelte';
    import Logout from './lib/login/Logout.svelte';
    import Message from './lib/common/Message.svelte';
    import Info from "./lib/info/Info.svelte";

</script>



<div class='w-full h-full flex flex-col'>
    <a href='/'>
        <img class='absolute w-20 mt-4 ml-24 no-print' src='../assets/bdrg_logo_r.png' alt='BDRG Logo'>
    </a>
    <div class='text-center no-print'>
        Das Rassegeflügel Zuchtbuch. Schönheit und Leistung durch Wissen.
    </div>

    <NavigationBar />

    <div class='grow p-2 flex flex-row gap-2 relative min-h-0'>

        <div class=''>
            <Menu />
        </div>

        <div class='grow flex flex-col border rounded bg-white items-center print'>

            <Route path='/' redirect='/zuchtbuch/1'/>
            <Route path='/zuchtbuch/*' let:meta>
                <Route path='/'> <Article articleId=1 /> </Route>
                <Route path='/:articleId' let:meta> <Article articleId={meta.params.articleId} /> </Route>
            </Route>
            <Route path='/standard/*' > <Standard /> </Route>
            <Route path='/leistungen/*' > <Results /> </Route>
            <Route path='/obmann/*' > <Moderator /> </Route>

            <Route path='/admin/*' > <Admin /> </Route>

            <Route path='/anmelden'> <Login /> </Route>
            <Route path='/reset'> <Reset /> </Route>
            <Route path='/abmelden'> <Logout /> </Route>

            <Route path='/kontakt/:districtId' let:meta> <Message districtId={meta.params.districtId} /> </Route>
        </div>

        {#if false}
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
        {/if}
    </div>

</div>

<style>


</style>

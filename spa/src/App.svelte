<script>
    //  import logo from './assets/svelte.png'
    import {Route} from 'tinro';

    import { standard, user } from './js/store.js'

    import NavigationBar from './lib/navigation/NavigationBar.svelte';
    import Menu from './lib/menu/Menu.svelte';
    import Article from './lib/article/Article.svelte';
    import Districts from './lib/districts/Districts.svelte';
    import Standard from './lib/standard/Standard.svelte';
    import Results from './lib/result/Results.svelte';

    import Moderator from './lib/moderator/Moderator.svelte';
    import Admin from './lib/admin/Admin.svelte';

    import Login from './lib/login/Login.svelte';
    import Reset from './lib/login/Reset.svelte';
    import Logout from './lib/login/Logout.svelte';
    import Message from './lib/common/Message.svelte';
    import api from './js/api.js';
    import Grading from './lib/result/Grading.svelte';

    api.standard.get().then( response => { // load standard here async into store
        standard.set( response.standard );
        console.log( 'Standard loaded', response.standard );
    } );

</script>

<div />
<Route path='/*' >
    <div class='w-full h-full flex flex-col'>
        <a href='/'>
            <img class='absolute w-20 mt-4 ml-24 no-print' src='../assets/bdrg_logo_r.png' alt='BDRG Logo'>
        </a>
        <div class='text-center no-print'>
            Das Rassegeflügel Zuchtbuch, Leistung und Schönheit durch Wissen.
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

                <Route path='/verband' > <Districts /> </Route>
                <Route path='/standard/*' > <Standard /> </Route>
                <Route path='/leistungen/*' >
                    <Route path='/'> <Results />  </Route>
                    <Route path='/nachweis'> <Grading />  </Route>

                </Route>

                <Route path='/obmann/*' > <Moderator /> </Route>

                <Route path='/admin/*' > <Admin /> </Route>

                <Route path='/anmelden'> <Login /> </Route>
                <Route path='/reset'> <Reset /> </Route>
                <Route path='/abmelden'> <Logout /> </Route>


                <Route path='/kontakt/:districtId' let:meta> <Message districtId={meta.params.districtId} /> </Route>
            </div>

        </div>

    </div>
</Route>

<style>


</style>

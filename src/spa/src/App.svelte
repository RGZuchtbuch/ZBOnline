<script>
	// https://imfeld.dev/writing/svelte_overlapping_transitions

    import { active, meta, router, Route } from 'tinro';
    import { fade, fly } from 'svelte/transition';
	import api from './scripts/api.js';
    import dic from './scripts/dic.js';

	import { user } from './scripts/store.js'

    import AdminRole from './pages/Admin.svelte';
    import BreederRole from './pages/Breeder.svelte';
    import Moderator from './pages/Moderator.svelte';
    import Page from './pages/Page.svelte';
    import Standard from './pages/Standard.svelte';

    import Results from './pages/Results.svelte';

    import Section from './components/Section.svelte';
    import Breeds from './components/Breeds.svelte';
    import Breed from './components/Breed.svelte';
    import Breeders from './components/Breeders.svelte';
    import Color from './components/Color.svelte';
    import Colors from './components/Colors.svelte';
    import Container from './components/Container.svelte';
    import District from './components/District.svelte';
    import Districts from './components/Districts.svelte';
    import Login from './components/Login.svelte';
    import SelectModerator from './components/SelectModerator.svelte';
    import Pair from './components/Pair.svelte';
    import Pairs from './components/Pairs.svelte';
    import Map from './components/Map.svelte';
    import Trend from './components/Trend.svelte';
    import Result from './components/Result.svelte';
    import ResultsFilter from './components/ResultsFilter.svelte';

    import Accordion, {AccordionItem} from './components/Accordion.svelte';


    import Flyer from "./components/Flyer.svelte";

	console.log( 'start' )

    router.mode.hash(); // using '#'

    let currentUser = null;
    user.subscribe( value => {
        currentUser = value;
        console.log( 'Current user', currentUser );
    });

</script>


<div id="app" class='w-full h-full flex flex-col justify-self-center border  bg-gray-50 relative'>

    <div class='flex flex-row gap-x-4 text-sm justify-between'>
        <ul class='flex flex-row gap-x-4'>
            RG Zuchtbuch :
            <li><a href="/#/" use:active>Das Zuchtbuch</a></li> -
            <li><a href="/#/standard">Standard</a></li> -
            <li><a href="/#/results">Leistungen</a></li> -
            {#if currentUser}
                <li><a href="/#/breeder">Mein Zuchtbuch</a></li> -
            {/if}
            {#if currentUser && currentUser.moderator.length > 0}
                <li><a href="/#/obmann">Obmann</a></li> -
            {/if}
            {#if currentUser && currentUser.isAdmin > 0}
                <li><a href="/#/admin">Admin</a></li> ---
            {/if}
        </ul>
        <ul>
            <li>
                {#if currentUser}
                    <a href='/#/'>Abmelden </a>{currentUser.name}
                {:else}
                    <a href='/#/login'>Anmelden</a>
                {/if}
            </li>
        </ul>
    </div>


    <div class='flex flex-row border grow overflow-y-auto'>

        <div class='grow border h-full border-yellow-600 relative overflow-y-auto'>

            <Container>
                <Route path='/'>
                    <Flyer>
                        <Page pageId={1}/>
                    </Flyer>
                </Route>

                <Route path='/seite/:pageId' let:meta>
                    <Flyer>
                        <Page pageId={meta.params.pageId}/>
                    </Flyer>
                </Route>

                <Route path='/standard/*' breadcrumb='standard' let:meta>
                    <Flyer>
                        <Standard/>
                    </Flyer>
                </Route>

                 {#if currentUser}
                    <Route path='/breeder/*' let:meta>
                        <Flyer>
                            <BreederRole promise={api.getUser( currentUser.id )} />
                        </Flyer>
                    </Route>
                {/if}

                {#if currentUser && currentUser.moderator.length > 0}
                    <Route path='/obmann/*' let:meta>
                        <Flyer>
                            <Moderator />
                        </Flyer>
                    </Route>
                {/if}

                <Route path='/admin/*' let:meta>
                    {#if currentUser && currentUser.isAdmin}
                        <Flyer>
                            <AdminRole/>
                        </Flyer>
                    {/if}
                </Route>

                <Route path='/login'>
                    <Flyer>
                        <Login />
                    </Flyer>
                </Route>

            </Container>
        </div>
    </div>
</div>



<style global>
	@tailwind base;
	@tailwind components;
	@tailwind utilities;

    nav {
        z-index: 10;
        grid-area: nav;
        color: rgb(220, 220, 220);
        background-color: rgb(30, 100, 30);
        font-size: 1.25rem;
        padding: 0.5rem;
    }

    li > a, a:visited {
        color:inherit;
    }

    h1 {
        @apply text-2xl font-bold text-amber-800;
    }
    h2 {
        @apply text-xl font-bold text-amber-800;
    }
    h3 {
        @apply text-lg font-bold text-amber-800;
    }
    h4 {
        @apply font-bold text-amber-800;
    }
    hr {
        @apply border border-gray-600 mt-2;
    }

    input:disabled {
        @apply bg-gray-50;
    }

</style>
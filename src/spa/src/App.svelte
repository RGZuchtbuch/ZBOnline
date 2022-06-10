<script>
	// https://imfeld.dev/writing/svelte_overlapping_transitions

    import { active, meta, router, Route } from 'tinro';
    import { fade, fly } from 'svelte/transition';
	import api from './scripts/api.js';
    import dic from './scripts/dic.js';

	import { user } from './scripts/store.js'

    import AdminRole from './pages/Admin.svelte';
    import BreederRole from './pages/Breeder.svelte';
    import ModeratorRole from './pages/Moderator.svelte';
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


    import Flyer from "./components/Flyer.svelte";

	console.log( 'start' )
	console.log( 'User', $user );

    router.mode.hash();

    let currentUser = null;
    user.subscribe( value => {
        currentUser = value;
        console.log( currentUser );
    });

</script>


<div id="app" class='flex flex-col justify-self-center border w-full h-full bg-gray-50 relative'>

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
                <li><a href="/#/moderator">Obmann</a></li> -
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
        <div class='flex flex-col w-48'>
            <Route path='/*'>
                <Flyer>
                    Home submenu
                </Flyer>
                <Container>
                    <Route path='/standard/*'>
                        <Flyer>
                            Standard
                        </Flyer>
                    </Route>
                    <Route path='/login/*'>
                        <Flyer>
                            Login
                        </Flyer>
                    </Route>
                </Container>
            </Route>


        </div>

        <div class='grow border h-full border-yellow-600 relative overflow-y-auto'>

            <Container>
                <Route path='/'>
                    <Flyer>
                        <Page/>
                    </Flyer>
                </Route>

                <Route path='/standard/*' breadcrumb='standard' let:meta>
                    <Flyer>
                        <Standard/>
                    </Flyer>
                </Route>

                <Route path='/results/*' let:meta>
                    <Flyer>
                        <ResultsFilter />
                        <Route path='/trend/*' let:meta>
                            <div class='container'>
                                <Route path='/:type/district/:districtId/section/:sectionId' let:meta>
                                    <Trend promise={api.getSectionTrend(meta.params.districtId, meta.params.sectionId)} type={meta.params.type} />
                                </Route>
                                <Route path='/:type/district/:districtId/breed/:breedIdId' let:meta>
                                    <Trend promise={api.getBreedTrend(meta.params.districtId, meta.params.sectionId)} type={meta.params.type} />
                                </Route>
                                <Route path='/:type/district/:districtId/color/:colorId' let:meta>
                                    <Trend promise={api.getColorTrend(meta.params.districtId, meta.params.sectionId)} type={meta.params.type} />
                                </Route>
                            </div>
                        </Route>
                        <Route path='/map/*' let:meta>
                            <div class='container'>
                                <Route path='/:type/year/:year/section/:sectionId' let:meta>
                                    <Flyer>
                                        <Map promise={api.getSectionMap(meta.year, meta.sectionId )} />
                                    </Flyer>
                                </Route>
                                <Route path='/:type/year/:year/breed/:breedId' let:meta>
                                    <Flyer>
                                        <Map promise={api.getBreedMap(meta.year, meta.breedId )} />
                                    </Flyer>
                                </Route>
                                <Route path='/:type/year/:year/color/:colorId' let:meta>
                                    <Flyer>
                                        <Map promise={api.getColorMap(meta.year, meta.colorId )} />
                                    </Flyer>
                                </Route>
                            </div>
                        </Route>
                        <Route path='/table' let:meta>
                            <div>Table over the years desc</div>
                            <Results promise={api.getSectionResults(meta.params.sectionId)} />
                        </Route>
                    </Flyer>
                </Route>

                <Route path='/breeder/*' let:meta>
                    {#if currentUser}
                        <Flyer>
                            <BreederRole promise={api.getUser( currentUser.id )} />
                        </Flyer>
                    {/if}
                </Route>

                <Route path='/moderator/*' let:meta>
                    {#if currentUser && currentUser.moderator.length > 0}
                        <Flyer>
                            <ModeratorRole/>
                        </Flyer>
                    {/if}
                </Route>

                <Route path='/admin/*' let:meta>
                    {#if currentUser && currentUser.isAdmin}
                        <Flyer>
                            <AdminRole/>
                        </Flyer>
                    {/if}
                </Route>

                <Route path='/district/:districtId/*' let:meta>
                    <Flyer>
                        <Route path='/' let:meta>
                            <District promise={api.district.get(meta.params.districtId)} />
                        </Route>
                        <Route path='/new' let:meta>
                            <District promise={api.district.new(meta.params.districtId)} />
                        </Route>
                        <Route path='/moderator/new' let:meta>
                            <SelectModerator promise={ api.moderator.new(meta.params.districtId) } />
                        </Route>


                    </Flyer>

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

</style>
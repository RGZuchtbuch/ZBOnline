<script>
	// https://imfeld.dev/writing/svelte_overlapping_transitions

    import { active, meta, router, Route } from 'tinro';
    import { fade, fly } from 'svelte/transition';
	import api from './scripts/api.js';
    import dic from './scripts/dic.js';

	import { user } from './scripts/store.js'

    import Admin from './pages/Admin.svelte';
    import Breeder from './pages/Breeder.svelte';
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

    function goto( path ) {
        return () => {
            router.goto(path);
        }
    }
/*


 */

</script>


<div id="app" class='h-full justify-self-center  border w-full'>

    <nav class='flex flex-row gap-x-4'>
        RG Zuchtbuch :
        <ul class='flex flex-row gap-x-4'>
            <li><a href="/#/" use:active>Das Zuchtbuch</a></li> -
            <li><a href="/#/standard">Standard</a></li> -
            <li><a href="/#/results">Leistungen</a></li> -
            <li><a href="/#/breeder">Mein Zuchtbuch</a></li> -
            <li><a href="/#/moderator">Obmann</a></li> -
            <li><a href="/#/admin">Admin</a></li> ---
            <li><a href="/#/login">Anmelden</a></li>
            {#if currentUser}{currentUser.name}{/if}
        </ul>
    </nav>


    <div class='flex flex-row border'>
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

        <div class='grow border'>

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
                    <Flyer>
                        {#if currentUser}
                            <Breeder promise={api.getUser( currentUser.id )} />
                        {:else}
                            Not logged in !
                        {/if}
                    </Flyer>

                </Route>

                <Route path='/moderator/*' let:meta>
                    <Flyer>
                        {#if currentUser}
                            <Moderator/>
                        {:else}
                        Not logged in !
                        {/if}
                    </Flyer>

                </Route>

                <Route path='/admin/*' let:meta>
                    <Flyer>
                        {#if currentUser}
                            <Admin/>
                        {:else}
                            Not logged in !
                        {/if}
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
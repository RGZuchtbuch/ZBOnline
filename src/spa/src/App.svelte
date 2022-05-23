<script>
	// https://imfeld.dev/writing/svelte_overlapping_transitions

    import { active, router, Route } from 'tinro';
    import { fade, fly } from 'svelte/transition';
	import api from './scripts/api.js';
    import dic from './scripts/dic.js';

	import { user } from './scripts/store.js'

    import Standard from './pages/Standard.svelte';
    import Section from './components/Section.svelte';
    import Breeds from './components/Breeds.svelte';
    import Breed from './components/Breed.svelte';
    import Breeders from './components/Breeders.svelte';
    import Breeder from './pages/Breeder.svelte';
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
    import Results from './components/Results.svelte';
    import ResultsFilter from './components/ResultsFilter.svelte';


    import Flyer from "./components/Flyer.svelte";

	console.log( 'start' )
	console.log( 'User', $user );

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


<div id="app" class='justify-center border'>

<div class='flex-flex-col border'>
    <Container>
        <nav>
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
    </Container>
    <div class='flex flex-row justify-center border'>
        <div class='flex flex-col w-48'>
            <Route path='/*'>
                <Flyer>
                    Home
                </Flyer>
            </Route>

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
        </div>

        <Container>
            <Route path='/'>
                <Flyer>
                    Home
                </Flyer>
            </Route>

            <Route path='/login'>
                <Flyer>
                    <Login />
                </Flyer>
            </Route>

            <Route path='/standard/*' let:meta>
                <Flyer>
                    <Route path='/'>
                        <Standard promise={api.getSections(1)} legend={dic.standard}/>
                    </Route>
                    <Route path='/:sectionId/*' let:meta>
                        <div>> Section Header: breeds, results</div>
                        <Section promise={api.getSection(meta.params.sectionId)} />
                        <Route path='/breeds/*' let:meta>
                            <div>> Breeds Header:</div>
                            <Route path='/' let:meta>
                                <Breeds promise={api.getSectionBreeds(meta.params.sectionId)} />
                            </Route>
                            <Route path='/:breedId/*' let:meta>
                                <div>> Breed Header:</div>
                                <Breed promise={api.getBreed(meta.params.breedId)} />
                                <Route path='/colors/:colorId' let:meta> cc <Color promise={api.getColor(meta.params.colorId)} /> </Route>
                                <Route path='/breeders' let:meta> <Breeders promise={api.getBreedBreeders(meta.params.breedId)} /> </Route>
                                <Route path='/results/*' let:meta>
                                    Results for breed: Trend (3 graphs), map/:year (3 maps), /data over years (table)
                                    <Route path='/trend' let:meta> <Trend promise={api.getBreedResults(meta.params.breedId)} /> </Route>
                                    <Route path='/map/*' let:meta>
                                        <div>Results map year select</div>
                                        <Route path='/' redirect={2020}/>
                                        <Route path='/:year' let:meta>
                                            <Map promise={api.getBreedDistrictsResults(meta.params.breedId, meta.params.year)} />
                                        </Route>
                                    </Route>
                                    <Route path='/table' let:meta>
                                        <div>Table over years</div>
                                        <Results promise={api.getBreedResults(meta.params.breedId)} />
                                    </Route>
                                </Route>
                            </Route>
                        </Route>

                        <Route path='/results/*' let:meta>
                            <div>Results for section: Trend (3 graphs), map/:year (3 maps), /data over years (table)</div>
                            <Route path='/trend' let:meta> <Trend promise={api.getSectionResults(meta.params.sectionId)} /> </Route>
                            <Route path='/map/*' let:meta>
                                <div>Results map year select, selects year, start with last year</div>
                                <Route path='/' redirect={2020}/>
                                <Route path='/:year' let:meta>
                                    <Map promise={api.getSectionDistrictsResults(meta.params.sectionId, meta.params.year)} />
                                </Route>
                            </Route>
                            <Route path='/table' let:meta>
                                <div>Table over the years desc</div>
                                <Results promise={api.getSectionResults(meta.params.sectionId)} />
                            </Route>
                        </Route>
                    </Route>
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
                        <Results promise={api.getUserResults( currentUser.id )} />
                    {:else}
                        Not logged in !
                    {/if}
                </Flyer>

            </Route>

            <Route path='/moderator/*' let:meta>
                <Flyer>
                    {#if currentUser}
                        <Breeder promise={api.getUser( currentUser.id )} />
                     <Districts promise={api.getModeratorDistricts( currentUser.id )} />
                    {:else}
                    Not logged in !
                    {/if}
                </Flyer>

            </Route>

            <Route path='/admin/*' let:meta>
                <Flyer>
                    {#if currentUser}
                        Admin
                    {:else}
                        Not logged in !
                    {/if}
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

    #aapp {
        display: grid;
        height: 100%;
        width: 100%;
        overflow: auto;
        grid-template:
      "nav" 3rem
      "main" 1fr
      / auto;
    }

    nav {
        z-index: 10;
        grid-area: nav;
        color: rgb(220, 220, 220);
        background-color: rgb(30, 100, 30);
        font-size: 1.25rem;
        padding: 0.5rem;
    }

</style>
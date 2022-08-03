<script>
    //  import logo from './assets/svelte.png'
    import {Route} from 'tinro';
    import api from './js/api.js';
    import Breeder from './lib/Breeder.svelte';
    import Breeders from './lib/Breeders.svelte';
    import District from './lib/District.svelte';
    import Districts from './lib/Districts.svelte';
    import Report from './lib/Report.svelte';
    import Reports from './lib/Reports.svelte';

</script>

<div class='w-full h-full border flex flex-col'>
    <img class='absolute w-32' src='../assets/bdrg_logo_r.png'>

    <div class='text-center'>
        Das Rassegeflügel Zuchtbuch. Schönheit und Leisting kombinieren.
    </div>
    <div class='border bg-gray-200 flex flex-row justify-between px-48 border rounded'>
        <div class='flex flex-row gap-x-4'>
            <a href=''>Das Zuchtbuch</a>
            <a href=''>Leistungen</a>
            <a href=''>Mein Zuchtbuch</a>
            <a href=''>Obmann</a>
            <a href=''>Admin</a>
        </div>
        <div>Anmelden</div>
    </div>

    <div class='mx-32 my-2 flex flex-row gap-2 relative min-h-0 '>
        <div class='w-48 border rounded'>
            left menu
        </div>


        <div class='grow bg-gray-100 overflow-y-scroll border border-black rounded p-4 scrollbar'>
            <Route path='/obmann/*'>
                <Route path='/verband/'>
                    <Districts legend={'Verbände für ?'} promise={api.moderator.districts(1)} link={'obmann/verband/'}/>
                </Route>
                <Route path='/verband/:districtId/*' let:meta>
                    <Route path='/' let:meta>
                        <District promise={api.district.get( meta.params.districtId) } />
                    </Route>
                    <Route path='/zuechter/*' let:meta>
                        <Route path='/' let:meta>
                            <Breeders promise={api.district.getBreeders(meta.params.districtId) } />
                            District Id {meta.params.districtId}, Breeders
                        </Route>
                        <Route path='/:breederId/*' let:meta>
                            <Route path='/' let:meta>
                                <Breeder promise={api.breeder.get(meta.params.breederId) } />
                            </Route>
                            <Route path='/meldung/*' let:meta>
                                <Route path='/' let:meta>
                                    <Reports promise={api.breeder.getReports(meta.params.breederId) } />
                                </Route>
                                <Route path='/:reportId/*' let:meta>
                                    <Route path='/' let:meta>

                                        <Report promise={api.report.get(meta.params.reportId) } />

                                    </Route>
                                </Route>
                            </Route>
                        </Route>
                    </Route>
                </Route>
            </Route>
        </div>

        <div class='w-48 border rounded'>info</div>
    </div>

</div>

<style>
    :root {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen,
        Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

</style>

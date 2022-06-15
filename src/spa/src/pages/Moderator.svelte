<script>
    import { active, meta, router, Route } from 'tinro';
    import Box from '../components/Box.svelte';
    import {user} from "../scripts/store";
    import api from '../scripts/api.js';

    import SelectTreeNode from '../components/SelectTreeNode.svelte';

    let currentUser = null;
    user.subscribe( value => {
        currentUser = value;
        console.log( 'User', currentUser );
    });

    console.log( 'Obmann' );

    function redirectWhenSingle( moderates ) {
        if( moderates.length === 1 ) {
            router.goto('/#/obmann/verband'+moderates[0].id);
            return true;
        }
        return false;
    }

    function onDistrictSelect( district ) {
        console.log( 'District selected', district );
        router.goto( '/obmann/verband/'+district.id );
    }

    function onBreederSelect( breeder ) {
        console.log( 'Breeder selected', breeder );
        router.goto( '/obmann/verband/'+breeder.district+'/zuechter/'+breeder.id );
    }

</script>

<div class=''>
    <h2 class='text-center'>Obmann {currentUser.name}</h2>

    <div class='flex flex-row justify-between border'>
        <div class='w-32'>
            <Box legend='Obmann'>
                {#if ! redirectWhenSingle( currentUser.moderator ) }
                    <div><a href={'/#/obmann'}>Verbände</a></div>
                {/if}

                <Route path='/verband/:districtId/*' let:meta>
                    {#await api.district.get( meta.params.districtId) then district}
                        <div>

                        <ul class='children'>
                            <li>{district.short}</li>
                            <br>
                            <li><a href={'/#/obmann/verband/'+meta.params.districtId}>Züchter</a></li>
                            <li><a href={'/#/obmann/verband/'+meta.params.districtId+'/meldungen'}>Meldungen</a></li>
                            <li><a href={'/#/obmann/verband/'+meta.params.districtId+'/zuechter'}>Leistungen</a></li>
                            <li><a href={'/#/obmann/verband/'+meta.params.districtId+'/zuechter'}>Berichte</a></li>
                        </ul>
                        </div>
                    {/await}
                </Route>
            </Box>
        </div>

        <Route path='/verband/:districtId/zuechter/:breederId/*' let:meta>
            <div class='w-32'>
                <Box legend='Züchter'>
                    {#await api.breeder.get(meta.params.breederId) then breeder}
                        {breeder.name}
                        <ul>
                            <li><a href={'/#/obmann/verband/'+breeder.district.id+'/zuechter/'+breeder.id}>Zuchterdaten</a></li>

                            <li><a href={'/#/obmann/verband/'+breeder.district.id+'/zuechter/'+breeder.id+'/meldung/new'}>Melden</a></li>
                            <li><a href={'/#/obmann/verband/'+breeder.district.id+'/zuechter/'+breeder.id+'/meldungen'}>Meldungen</a></li>
                        </ul>
                    {/await}

                </Box>
            </div>
        </Route>

        <div class='w-160'>
            <Box legend='Arbeitsfläche'>
                <Route path='/' let:meta>
                    <div>Welcher Verband möchtest du verwalten :</div>
                    {#await api.moderator.districts( currentUser.id ) then districts}
                        <SelectTreeNode children={districts} onSelect={onDistrictSelect}/>
                    {/await}
                </Route>

                <Route path='/verband/:districtId/*' let:meta>
                    <Route path='/' let:meta>
                        <div>Welcher Züchter möchtest du verwalten :</div>
                        {#await api.district.breeders( meta.params.districtId) then breeders}
                            <SelectTreeNode children={breeders} onSelect={onBreederSelect}/>
                        {/await}
                    </Route>

                    <Route path='/zuechter/:breederId/*' let:meta>
                        <Route path='/meldung/:pairId' let:meta>
                            <div>Meldung</div>
                            {#if meta.params.pairId === 'new'}
                                {#await api.pair.new( meta.params.breederId ) then pair}
                                    <div>Breeder ({pair.breeder.id}) {pair.breeder.name}</div>
                                    <div>Year:{pair.name}, name:{pair.name}, Group:{pair.group}</div>
                                    <div>Section → Breed → [Color] select</div>

                                    <div>
                                        <div>Parents</div>
                                        {#each pair.parents as parent}
                                            <div>{parent.sex} {parent.country}{parent.ring}{parent.score},parents:{parent.parents} show 3 years history</div>
                                        {/each}
                                    </div>

                                    <div>Lay: {pair.lay.start} - {pair.lay.until} : {pair.lay.eggs}</div>

                                    <div>
                                        Brood
                                        {#each pair.broods as brood}
                                            <div>{brood.start} → {brood.eggs} → {brood.fertile} → {brood.hatched}</div>
                                        {/each}
                                    </div>

                                    <div>
                                        Show
                                        {#each Object.keys(pair.scores) as key}
                                            {key} : {pair.scores[ key ]}
                                        {/each}
                                    </div>
                                {/await}
                            {:else}
                                {#await api.pair.get( meta.params.pairId ) then report}
                                    report
                                {/await}
                            {/if}
                        </Route>
                        <Route path='/meldungen' let:meta>
                            <div>Meldung</div>
                            {#if meta.params.reportId === 'new'}
                                new
                            {:else}
                                {#await api.breeder.getPairs(meta.params.breederId) then pairs}
                                    pair
                                {/await}
                            {/if}
                        </Route>
                    </Route>
                </Route>

            </Box>
        </div>

        <div></div>
    </div>
</div>

<style>
    ul.children {
        @apply list-disc;
    }
    ul.children > li {
        @apply ml-4;
    }
</style>
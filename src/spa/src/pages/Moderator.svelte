<script>
    import { scale } from 'svelte/transition';
    import { active, meta, router, Route } from 'tinro';
    import {user} from "../scripts/store";
    import api from '../scripts/api.js';
    import Box from '../components/Box.svelte';
    import Pair from '../components/Pair.svelte';

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

    <div class='flex flex-row justify-between border gap-2'>
        <div class='w-48'>
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
                            <li><a href={'/#/obmann/verband/'+meta.params.districtId}>Züchter</a>
                                <Route path='/zuechter/:breederId/*' let:meta>
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

                            </li>
                            <li><a href={'/#/obmann/verband/'+meta.params.districtId+'/meldungen'}>Meldungen</a></li>
                            <li><a href={'/#/obmann/verband/'+meta.params.districtId+'/zuechter'}>Leistungen</a></li>
                            <li><a href={'/#/obmann/verband/'+meta.params.districtId+'/zuechter'}>Berichte</a></li>
                        </ul>
                        </div>
                    {/await}
                </Route>
            </Box>
        </div>

        <Route path='/_verband/:districtId/zuechter/:breederId/*' let:meta>
            <div transition:scale >
                <div class='w-32'>
                <Box legend='Züchteer'>
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
            </div>
        </Route>

        <div class='w-160'>
            <Box legend='Arbeitsfläche'>
                <Route path='/' let:meta>
                    <div>Welcher Verband möchtest du verwalten :</div>
                    {#await api.moderator.districts( currentUser.id ) then districts}
                        <SelectTreeNode children={districts} onSelect={onDistrictSelect} link={'/#/obmann/verband/'}/>
                    {/await}
                </Route>

                <Route path='/verband/:districtId/*' let:meta>
                    <Route path='/' let:meta>
                        <div>Welcher Züchter möchtest du verwalten :</div>
                        {#await api.district.breeders( meta.params.districtId) then breeders}
                            <SelectTreeNode children={breeders} onSelect={onBreederSelect} link={'/#/obmann/verband/'+meta.params.districtId+'/zuechter/'}/>
                        {/await}
                    </Route>

                    <Route path='/zuechter/:breederId/*' let:meta>
                        <Route path='/meldung/:pairId' let:meta>
                            <Pair promise={api.pair.get( meta.params.breederId, meta.params.pairId )} />
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
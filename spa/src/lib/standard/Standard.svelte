<script>
    import { Route } from 'tinro';

    import Breeds from "./Sections.svelte";
    import Breed from "./Breed.svelte";
    import Color from "./Color.svelte";


</script>

<Route path='/'>
    <Breeds />
</Route>

<!-- for later extension to open breed directly from url query
    <Route path='/sparte/*'>

        <Route path='/'> ? </Route>
        <Route path='/:sectionId/rasse/:breedId/*' let:meta>
            <Breed params={meta.params} >
                <Route path='/farbe/:colorId' let:meta>
                    <Color params={meta.params} />
                </Route>
            </Breed>
        </Route>

    </Route>
-->

<style>

</style>
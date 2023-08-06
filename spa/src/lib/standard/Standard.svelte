<script>
    import { meta, router, Route } from 'tinro';
    import api from '../../js/api.js';
    import Sections from './sections.old/Sections.svelte';
    import SectionRow from './sections.old/SectionRow.svelte';
    import Breeds from "./Breeds.svelte";
    import Breed from "./Breed.svelte";


</script>

<Route path='/'> <Breeds /> </Route>
<Route path='/sparte/*'>

    <Route path='/'> ? </Route>
    <Route path='/:sectionId/rasse/:breedId' let:meta> <Breed params={meta.params} /> </Route>
    <Route path='/:sectionId/rasse/:breedId/farbe/:colorId' let:meta> <Breed params={meta.params} /> </Route>

</Route>


<style>

</style>
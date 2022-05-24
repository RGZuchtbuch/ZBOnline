<script>
    import { active, meta, router, Route } from 'tinro';

    import api from '../scripts/api.js';
    import dic from '../scripts/dic.js';

    import Box from '../components/Box.svelte';
    import Breed from '../components/Breed.svelte';
    import Breeds from '../components/Breeds.svelte';
    import Breeders from '../components/Breeders.svelte';
    import Color from '../components/Color.svelte';
    import Colors from '../components/Colors.svelte';
    import Section from '../components/Section.svelte';
    import Sections from '../components/Sections.svelte';

    const route = meta();

    console.log( 'Page Standard', route );

</script>

<Box legend='Rasseverzeichnis der BDRG' href='/#/standard' >
    <div class='w-128'>
        <Route path='/'>
            <Sections promise={api.getSections(2)} legend={'Gruppen'}/>
        </Route>
        <Route path='/section/:sectionId/*' breadcrumb='section' let:meta>
            <Section promise={api.getSection(meta.params.sectionId)} />
        </Route>
        <Route path='/breed/:breedId/*' let:meta>
            <Breed promise={api.getBreed(meta.params.breedId)} />
            <Route path='/color/:colorId' let:meta>
                <Color promise={api.getColor(meta.params.colorId)} />
            </Route>
        </Route>
    </div>
</Box>
<style>

</style>
<script>
    import {Route, meta} from 'tinro';
    import api from '../../js/api.js';

    import Districts from './Districts.svelte';
    import Pages from './Pages.svelte';
    import Sections from './Sections.svelte';

    const route = meta();

</script>

<Route path='/' redirect={ route.match+'/verband'} />


<Route path='/standard'> <Sections /> </Route>
<Route path='/verband'> <Districts /> </Route>

<Route path='/seite' /> <Pages></Pages>



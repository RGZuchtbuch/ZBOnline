<script>
    import { meta, router, Route } from 'tinro';
    import { breeder, district, user } from '../../js/store.js';

    import District from '../district/District.svelte';
    import Districts from './Districts.svelte';
    import Grading from '../grading/Calculator.svelte';

    const route = meta();
    console.log( 'Moderator route', route);

</script>


<slot>Hier sollte man nur enden wenn mann angemeldet und Admin ist</slot>


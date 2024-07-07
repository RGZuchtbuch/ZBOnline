<script>
	//  import logo from './assets/svelte.png'
	import {meta, Route} from 'tinro';
	import api from './js/api.js';
	import { breeder, district, standard, user } from './js/store.js'

	import NavigationBar from './lib/navigation/NavigationBar.svelte';
	//    import Menu from './lib/menu/Menu.svelte';
	import Admin from './lib/admin/Admin.svelte';
	import Menu from './lib/navigation/Menu.svelte';
	import Article from './lib/article/Article.svelte';
	import Breeder from './lib/breeder/Breeder.svelte';

	import Districts from './lib/districts/Districts.svelte';
	import District from './lib/district/District.svelte';
	import DistrictBreeders from './lib/district/Breeders.svelte';
	import DistrictDetails from './lib/district/DistrictDetails.svelte';
	import DistrictReport from './lib/district/DistrictReport.svelte';

	import GradingCalculator from './lib/grading/Calculator.svelte';
	import GradingForm from './lib/grading/Form.svelte';

	import Moderator from './lib/moderator/Moderator.svelte';
	import ModeratorDistricts from './lib/moderator/Districts.svelte';
	import Results from './lib/result/Results.svelte';
	import Standard from './lib/standard/Standard.svelte';


	import Login from './lib/login/Login.svelte';
	import Reset from './lib/login/Reset.svelte';
	import Logout from './lib/login/Logout.svelte';
	import Message from './lib/common/Message.svelte';
	import ResultEdit from './lib/result/edit/Edit.svelte';
	import BreederPairs from './lib/breeder/Pairs.svelte';
	import BreederDetails from './lib/breeder/BreederDetails.svelte';
	import Pair from './lib/pair/Pair.svelte';

	api.standard.get().then( response => { // load standard here async into store
		standard.set( response.standard );
		console.log( 'Standard loaded', response.standard );
	} );

</script>


<Route path='/*' >
	<Menu />

	<!-- div class='relative grow flex flex-col mmin-h-0 print' --> <!-- min-h-0 for scroll -->


	<!-- div class='grow flex flex-col border rounded bg-white items-center print' -->

	<Route path='/' redirect='/zuchtbuch/1'/>

	<Route path='/zuchtbuch/*' let:meta>
		<Route path='/'> <Article articleId=1 /> </Route> <!-- should met list of artikels -->
		<Route path='/:articleId' let:meta> <Article articleId={meta.params.articleId} /> </Route>
	</Route>

	<Route path='/verband' > <Districts /> </Route>

	<Route path='/standard' > <Standard /> </Route>

	<Route path='/leistungen/*' >
		<Route path='/'> <Results />  </Route>
		<Route path='/nachweis'> <GradingForm />  </Route>
		<Route path='/rechner'> <GradingCalculator />  </Route>
	</Route>

	<Route path='/obmann/*' >
		<Moderator> <!-- uses users from store and relays to login is needed -->
			<Route path='/verband/*'>
				<Route path='/'> <ModeratorDistricts/> </Route>
				<Route path='/:districtId/*' let:meta>

					<District id={meta.params.districtId} >
						<Route path='/zuechter/*' let:meta>
							<Route path='/' let:meta> <DistrictBreeders /> </Route>
							<Route path='/:breederId/*' let:meta >
								<Breeder id={meta.params.breederId} >
									<Route path='/' let:meta> <BreederDetails /> </Route>
									<Route path='/meldung/*' let:meta>
										<Route path='/' let:meta> <BreederPairs breederId={meta.params.breederId} /> </Route>
										<Route path='/:pairId/*' let:meta>
											<Route path='/' let:meta>
												<Pair id={Number(meta.params.pairId)} districtId={Number(meta.params.districtId)} breederId={Number(meta.params.breederId)}/>
											</Route>
										</Route>
									</Route>
								</Breeder>
							</Route>
						</Route>
						<Route path='/leistung/*' let:meta>
							<Route path='/' let:meta> <DistrictReport districtId={ +meta.params.districtId } /> </Route>
							<!--Route path='/edit' let:meta> <ResultEdit districtId={ +meta.params.districtId }  /></Route-->
						</Route>
						<Route path='/daten' let:meta> <DistrictDetails {districtId} /> </Route>
					</District>
					<!--District districtId={meta.params.districtId} /-->

				</Route>
			</Route>
			<Route path='/nachweis'> <Grading /> </Route>
		</Moderator>

	</Route>

	<Route path='/admin/*' >
		<Admin />
	</Route>

	<Route path='/anmelden'> <Login /> </Route>
	<Route path='/reset'> <Reset /> </Route>
	<Route path='/abmelden'> <Logout /> </Route>


	<Route path='/kontakt/:districtId' let:meta> <Message districtId={meta.params.districtId} /> </Route>
</Route>

<style></style>

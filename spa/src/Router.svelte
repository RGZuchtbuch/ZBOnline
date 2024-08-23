<script>
	//  import logo from './assets/svelte.png'
	import {meta, Route, router} from 'tinro';
	import api from './js/api.js';
	import {toNumber} from './js/util.js';
	import { standard, user } from './js/store.js'

	import Admin from './lib/admin/Admin.svelte';
	import AdminDistricts from './lib/admin/Districts.svelte';
	import Article from './lib/article/Article.svelte';

	import Breeder from './lib/breeder/Breeder.svelte';
	import BreederPairs from './lib/breeder/Pairs.svelte';
	import BreederDetails from './lib/breeder/BreederDetails.svelte';

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

	import Menu from './lib/menu/Menu.svelte';

	import Login from './lib/login/Login.svelte';
	import Reset from './lib/login/Reset.svelte';
	import Logout from './lib/login/Logout.svelte';
	import Message from './lib/common/Message.svelte';
	import Pair from './lib/pair/Pair.svelte';
	import DistrictResultsEdit from './lib/district/DistrictResultsEdit.svelte';
	import Log from './lib/admin/log/Log.svelte';
	import Articles from './lib/admin/Articles.svelte';
	import Trend from './lib/breeder/Trend.svelte';
</script>

<!-- the router here is responsible for converting params to numbers using isNumber if appropriate -->
<Route path='/*' >
	<Menu />

	<Route path='/'> <Article articleId={1} /> </Route> <!-- front page -->

	<Route path='/zuchtbuch/*' let:meta>
		<Route path='/'> <Article articleId={1} /> </Route> <!-- should show list of artikels -->
		<Route path='/:articleId' let:meta> <Article articleId={toNumber(meta.params.articleId)} /> </Route>
	</Route>

	<Route path='/verband' > <Districts /> </Route>

	<Route path='/standard' > <Standard /> </Route>

	<Route path='/leistungen/*' >
		<Route path='/'> <Results />  </Route>
		<Route path='/nachweis'> <GradingForm />  </Route>
		<Route path='/rechner'> <GradingCalculator />  </Route>
	</Route>

	<Route path='/obmann/*' >

		<Moderator> <!-- uses user from store and relays to login if needed -->
			<Route path='/verband/*'>
				<Route path='/'> <ModeratorDistricts/> </Route>
				<Route path='/:districtId/*' let:meta>

					<District id={toNumber(meta.params.districtId)} >
						<Route path='/details' let:meta> <DistrictDetails /> </Route>
						<Route path='/zuechter/*' let:meta>
							<Route path='/' let:meta> <DistrictBreeders /> </Route>
							<Route path='/:breederId/*' let:meta >

								<Breeder id={toNumber(meta.params.breederId)} >
									<Route path='/details' let:meta> <BreederDetails /> </Route>
									<Route path='/meldung/*' let:meta>
										<Route path='/' let:meta> <BreederPairs breederId={toNumber(meta.params.breederId)} /> </Route>
										<Route path='/:pairId/*' let:meta>
											<Route path='/' let:meta>
												<Pair id={toNumber(meta.params.pairId)} districtId={toNumber(meta.params.districtId)} breederId={toNumber(meta.params.breederId)}/>
											</Route>
										</Route>
									</Route>
									<Route path='/entwicklung' let:meta> <Trend /> </Route>
								</Breeder>
							</Route>
						</Route>

						<Route path='/leistung/*' let:meta>
							<Route path='/' let:meta> <DistrictReport districtId={toNumber(meta.params.districtId)} year={new Date().getFullYear()} /> </Route>
							<Route path='/:year' let:meta> <DistrictReport districtId={toNumber(meta.params.districtId)} year={toNumber(meta.params.year)} /> </Route>
							<Route path='/:year/edit' let:meta> <DistrictResultsEdit districtId={toNumber(meta.params.districtId)} year={toNumber(meta.params.year)} /> </Route>
							<!--Route path='/edit' let:meta> <ResultEdit districtId={ +meta.params.districtId }  /></Route-->
						</Route>

					</District>
					<!--District districtId={meta.params.districtId} /-->

				</Route>
			</Route>
		</Moderator>

	</Route>

	<Route path='/admin/*' >
		<Admin>
			<Route path='/' >
				<AdminDistricts />
			</Route>

			<Route path='/standard/*'>
				<Standard />
			</Route>

			<Route path='/verband/*'>
				<Route path='/'>
					<AdminDistricts />
				</Route>
				<Route path='/:districtId/*' let:meta>
					<District id={toNumber(meta.params.districtId)} >
						<Route path='/' let:meta> <DistrictDetails districtId={toNumber(meta.params.districtId)} /> </Route>
						<Route path='/details' let:meta> <DistrictDetails districtId={toNumber(meta.params.districtId)} /> </Route>

						<Route path='/zuechter/*' let:meta>
							<Route path='/' let:meta> <DistrictBreeders /> </Route>
							<Route path='/:breederId/*' let:meta >
								<Breeder id={toNumber(meta.params.breederId)} >
									<Route path='/details' let:meta> <BreederDetails /> </Route>
									<Route path='/meldung/*' let:meta>
										<Route path='/' let:meta> <BreederPairs breederId={toNumber(meta.params.breederId)} /> </Route>
										<Route path='/:pairId/*' let:meta>
											<Route path='/' let:meta>
												<Pair id={toNumber(meta.params.pairId)} districtId={toNumber(meta.params.districtId)} breederId={toNumber(meta.params.breederId)}/>
											</Route>
										</Route>
									</Route>
								</Breeder>
							</Route>
						</Route>

						<Route path='/leistung/*' let:meta>
							<Route path='/' let:meta> <DistrictReport districtId={toNumber(meta.params.districtId)} year={new Date().getFullYear()} /> </Route>
							<Route path='/:year' let:meta> <DistrictReport districtId={toNumber(meta.params.districtId)} year={toNumber(meta.params.year)} /> </Route>
							<Route path='/:year/edit' let:meta> <DistrictResultsEdit districtId={toNumber(meta.params.districtId)} year={toNumber(meta.params.year)} /> </Route>
							<!--Route path='/edit' let:meta> <ResultEdit districtId={ +meta.params.districtId }  /></Route-->
						</Route>
					</District>
				</Route>
			</Route>

			<Route path='/seite' >
				<Articles />
			</Route>

			<Route path='/log' >
				<Log />
			</Route>

		</Admin>
	</Route>

	<Route path='/anmelden'> <Login /> </Route>
	<Route path='/reset'> <Reset /> </Route>
	<Route path='/abmelden'> <Logout /> </Route>

	<Route path='/kontakt/:districtId' let:meta> <Message districtId={meta.params.districtId} /> </Route>
</Route>

<style></style>

// configuration file providings consts yet having some convienience fuctions





/* setting the current input year for new input and reports */

const CURRENT_INPUT_YEAR_DELAYED = 2; // 2 months into the new year for input

function findCurrentInputYear() {
	let inputDate = new Date();
	inputDate.setMonth(inputDate.getMonth() - CURRENT_INPUT_YEAR_DELAYED ); // note the 2 means upto march first
	return inputDate.getFullYear();
}

const START_YEAR = 1980;
const TREND_YEARS_COUNT = 10; // number of years to show in bar chart for trend in report.
const CURRENT_INPUT_YEAR = findCurrentInputYear();
const CURRENT_REPORT_YEAR = findCurrentInputYear() - 1; // as the current input year is not completed yet


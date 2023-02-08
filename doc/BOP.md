Routing

Niet per link, maar events naar bv Standard page en die regelt de rest voor menu en content.

Dit voor meer overzicht.
bv offset
* standard
dan 
* standard/sections tree
* standard/section/:section with breedsnames
* standard/breed:/breed with colornames and result trend?
* standard/breed/:breed/color/:color

voor breeder
* breeder/:breeder ( by breeder, moderator, admin )
dan
* breeder/:breeder/pairs/results for breeder, year+code desc visible for breeder, moderator
* pair/:pair details with pedegree editable for breeder, moderator, admin
* pair/0 new pair for current breeder
	breeder, year, code
	breed, color, 
	pedegree: sire and dames and their pedegree pair ( with results ), maybe inbreed value ?
	Laying ( for some sections ): from, to, egs, henns (from pedegree above) → laying result calc.
	Brood: date, eggs, fertile, hatched → percentages calculated
	Show: nr per grade ( what to do with U or O ) → average calc.
	Result bar with deducted values.
	
	below pairs entered list.

* breeder/:breeder/results per year/breed combine pairs desc met trend
* breeder/:breeder/result -> pairs and detail per pair

for moderator
* districts
* district/:district with moderator, editable by admin
* district/:district/breeders
* district/:district/results per year

* breeder/:breeder etc...
* breeder/0 new breeder for district

for admin
* district/:district select moderator from list , maybe multiple ?
* generate reports
  * year report per district.
  

roles to enforce in front and backend
* breeder, 
* moderator,
* editor for standard
* admin,



report gen: if higher leve defined
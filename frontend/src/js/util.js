function extendYear( year, max ) {
    if( year >=0 && year < 100 ) {
        const maxYear = max.getFullYear() % 100;
        if( year > maxYear ) {
            return max.getFullYear() - 100 - maxYear + year;
        } else {
            return max.getFullYear() - maxYear + year;
        }
    }
    return year;
}


export function getValidDate( input, min, max ) {
    if ( typeof input === 'string' ) {
        min = new Date( min );
        max = new Date( max );
        let date = null;
        let match; // D
        match = input.match(/^([0-9]{4})[\-\.](1[0-2]|0[1-9]|[1-9])[\-\.](3[0-1]|[12][0-9]|0[1-9]|[1-9])$/); // iso 2022-7-22
        if( match ) {
            date = new Date( extendYear( parseInt( match[1] ), max), parseInt( match[2] )-1, parseInt( match[3] )) // yyyy, mm, dd
        } else {
            match = input.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\-\.](1[0-2]|0[1-9]|[1-9])[\-\.]([0-9]{2}|[0-9]{4})$/); // 31-01-22 or 31.01.22
            if (match) {
                date = new Date( extendYear( parseInt( match[3] ), max ), parseInt( match[2] )-1, parseInt( match[1] )) // yyyy, mm, dd
            }
        }

        if( date && date >= min && date <= max ) return date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();
    }
    return null;
}




export function getProduction( days, eggs, dames ) {
    let fit;
    if( days > 365 ) {
        fit = 365;
    } else if( days > 183 ) {
        fit = 340.1984 - 0.7930455*days + 0.002358891*days*days;;  // https://mycurvefit.com/
    } else {
        fit = 274;
    }
    const production = eggs/dames * fit/days; //fit * eggs/days/dames;
    if( production >= 0 && production <= 366 ) {
        console.log( 'P', production );
        return production;
    }
    return null;
}
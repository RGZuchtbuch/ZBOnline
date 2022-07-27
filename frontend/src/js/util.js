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

function isDateInRange( date, min, max ) {
    return date && date >= min && date <= max;
}


export function getValidDate( input, min, max ) {
    let match; // D
    if (input !== null) {
        match = input.match(/^([0-9]{4})\-(1[0-2]|0[1-9]|[1-9])\-(3[0-1]|[12][0-9]|0[1-9]|[1-9])$/); // iso 2022-7-22
        if( match ) {
            const date = new Date( extendYear( parseInt( match[1] ), max), parseInt( match[2] )-1, parseInt( match[3] )) // yyyy, mm, dd
            if( isDateInRange( date, min, max ) ) return date;
        } else {
            match = input.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\-\.](1[0-2]|0[1-9]|[1-9])[\-\.]([0-9]{2}|[0-9]{4})$/); // 31-01-22 or 31.01.22
            if (match) {
                const date = new Date( extendYear( parseInt( match[3] ), max ), parseInt( match[2] )-1, parseInt( match[1] )) // yyyy, mm, dd
                if( isDateInRange( date, min, max ) ) return date;
            }
        }
    }
    return null;
}
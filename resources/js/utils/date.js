import moment from 'moment/min/moment-with-locales';

export function isoDateFormat(value) {
    if (!value) {
        return null;
    }
    return moment(value).format(moment.HTML5_FMT.DATE);
}

export function dateFormat(value) {
    if (!value) {
        return null;
    }
    return moment(value).format("LL ");
}

export function dateWeekdayFormat(value) {
    if (!value) {
        return null;
    }
    return moment(value).format("dddd, LL");
}

export function dateTimeFormat(value) {
    if (!value) {
        return null;
    }
    return moment(value).format("LLL");
}

export function timeFormat(value) {
    if (!value) {
        return null;
    }
    return moment(value).format("HH:mm");
}

export function timeFromNow(value) {
    return moment(value).fromNow();
}

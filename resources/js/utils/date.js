import moment from "moment";

export function dateFormat(value) {
    if (!value) {
        return null;
    }
    return moment(value).format("LL");
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

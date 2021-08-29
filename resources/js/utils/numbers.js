import numeral from "numeral";

export function numberFormat(value) {
    return numeral(value).format("0,0");
}

export function decimalNumberFormat(value) {
    return numeral(value).format("0,0.00");
}

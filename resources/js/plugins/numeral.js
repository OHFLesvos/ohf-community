import numeral from 'numeral'
export default function(value) {
    return numeral(value).format('0,0')
}

import numberFormat from '@/plugins/numeral'
import { roundWithDecimals } from '@/utils'
export default {
    methods: {
        numberFormat,
        roundWithDecimals,
        percentValue (value, total) {
            return roundWithDecimals(value / total * 100, 1)
        }
    }
}

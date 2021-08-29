import { numberFormat, decimalNumberFormat } from "@/utils/numbers";
import { roundWithDecimals } from "@/utils";
export default {
    methods: {
        numberFormat,
        decimalNumberFormat,
        roundWithDecimals,
        percentValue(value, total) {
            return roundWithDecimals((value / total) * 100, 1);
        }
    }
};

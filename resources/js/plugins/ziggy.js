import ziggyRoute from 'ziggy-js'
import { Ziggy } from '@/ziggy'
export default function (name, params) {
    return ziggyRoute(name, params, false, Ziggy)
}

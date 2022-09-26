import ziggyRoute from 'ziggy'
import { Ziggy } from '@/ziggy'
export default function (name, params) {
    return ziggyRoute(name, params, false, Ziggy)
}

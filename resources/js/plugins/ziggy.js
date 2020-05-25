import ziggyRoute from 'ziggy'
import { Ziggy } from '@/ziggy'
export default {
    route (name, params) {
        return ziggyRoute(name, params, false, Ziggy)
    }
}

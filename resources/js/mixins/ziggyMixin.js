import route from 'ziggy'
import { Ziggy } from '@/ziggy'
export default {
    methods: {
        route: (name, params) => route(name, params, false, Ziggy),
        // route: (name, params, absolute) => route(name, params, absolute, Ziggy),
    }
}

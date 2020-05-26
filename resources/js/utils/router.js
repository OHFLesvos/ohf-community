import store from '@/store'

export const rememberRoute = function(to, from, ignore = []) {
    if (from.name != null && !ignore.find(e => e == from.name)) {
        // console.log(`remember route to ${to.name} from ${from.name}`)
        store.commit('SET_PREVIOUS_ROUTE', {
            name: to.name,
            value: from
        })
    }
}

export const previouslyRememberedRoute = function(name, defaultValue) {
    return store.getters.getPreviousRoute(name, defaultValue)
}

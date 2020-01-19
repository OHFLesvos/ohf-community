export default class SessionVariable {
    constructor(key) {
        this.key = key
    }

    set(value) {
        sessionStorage.setItem(this.key, value)
    }

    get(defaultValue = '') {
        const value = sessionStorage.getItem(this.key)
        if (value !== undefined && value != null && value.length > 0) {
            return value
        }
        return defaultValue
    }

    forget() {
        sessionStorage.removeItem(this.key)
    }
}
export default class LocalVariable {
    constructor(key) {
        this.key = key
    }

    set(value) {
        localStorage.setItem(this.key, value)
    }

    get(defaultValue = '') {
        const value = localStorage.getItem(this.key)
        if (value !== undefined && value != null && value.length > 0) {
            return value
        }
        return defaultValue
    }

    forget() {
        localStorage.removeItem(this.key)
    }
}
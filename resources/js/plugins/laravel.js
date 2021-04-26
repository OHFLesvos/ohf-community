const permissions = window.Laravel.permissions;

export function can(key) {
    return permissions && permissions[key] != undefined
        ? permissions[key]
        : false;
}

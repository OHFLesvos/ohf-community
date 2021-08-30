const baseTitle = window.Laravel.title;

function getTitle(vm) {
    const { title } = vm.$options;
    if (title) {
        return typeof title === "function" ? title.call(vm) : title;
    }
}
export default {
    created() {
        let title = getTitle(this);
        if (title) {
            if (baseTitle && baseTitle.length > 0) {
                title += ' - ' + baseTitle;
            }
            document.title = title;
        }
    }
};

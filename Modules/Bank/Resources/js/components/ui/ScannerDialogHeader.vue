<script>
import ScannerDialogHeaderTitle from './ScannerDialogHeaderTitle';
import ScannerDialogHeaderButtons from './ScannerDialogHeaderButtons';

export default {
    functional: true,
    props: ['title', 'value'],
    render(createElement, context) {
        const title = createElement(ScannerDialogHeaderTitle, {
            props: context.props
        })
        const buttons = createElement(ScannerDialogHeaderButtons, {
            props: context.props,
            on: {
                close: context.listeners.close,
                input: context.listeners.input
            }
        })
        return [title, buttons]
    }
}
</script>
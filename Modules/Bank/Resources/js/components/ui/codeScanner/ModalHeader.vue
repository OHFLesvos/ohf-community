<script>
import ModalHeaderTitle from './ModalHeaderTitle';
import ModalHeaderButtons from './ModalHeaderButtons';

export default {
    functional: true,
    props: ['title', 'value'],
    render(createElement, context) {
        const title = createElement(ModalHeaderTitle, {
            props: context.props
        })
        const buttons = createElement(ModalHeaderButtons, {
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
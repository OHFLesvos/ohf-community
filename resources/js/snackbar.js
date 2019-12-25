import Snackbar from 'node-snackbar'
export default function showSnackbar(message) {
    Snackbar.show({
        text: message,
        duration: 2500,
        pos: 'bottom-center',
        actionText: null,
        actionTextColor: null,
    });
}
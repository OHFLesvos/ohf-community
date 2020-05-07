import axios from '@/plugins/axios'
import ziggyMixin from '@/mixins/ziggyMixin'
const route = ziggyMixin.methods.route

export async function findIsbn (isbn) {
    const url = route('api.library.books.findIsbn', {
        isbn: isbn
    })
    const res = await axios.get(url)
    return res.data
}

export async function storeBook (data) {
    const url = route('api.library.books.store')
    const res = await axios.post(url, {
        ...data
    })
    return res.data
}

export async function findBook(id) {
    const url = route('api.library.books.show', [id])
    const res = await axios.get(url)
    return res.data
}

export async function updateBook(id, data) {
    const url = route('api.library.books.update', [id])
    const res = await axios.put(url, {
        ...data
    })
    return res.data
}

export async function deleteBook(id) {
    const url = route('api.library.books.destroy', [id])
    const res = await axios.delete(url)
    return res.data
}

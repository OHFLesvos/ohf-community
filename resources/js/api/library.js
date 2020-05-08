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

export async function findLendingsOfPerson(id) {
    const url = route('api.library.lending.person', [id])
    const res = await axios.get(url)
    return res.data
}

export async function lendBookToPerson(book, personId) {
    const url = route('api.library.lending.lendBookToPerson', [personId])
    let data
    if (typeof book == 'object') {
        data = {
            ...book
        }
    } else {
        data = {
            book_id: book
        }
    }
    const res = await axios.post(url, data)
    return res.data
}

export async function extendLending(bookId, personId, days) {
    const url = route('api.library.lending.extendBookToPerson', [personId])
    const res = await axios.post(url, {
        book_id: bookId,
        days: days
    })
    return res.data
}

export async function returnBook(bookId, personId) {
    const url = route('api.library.lending.returnBookFromPerson', [personId])
    const res = await axios.post(url, {
        book_id: bookId,
    })
    return res.data
}

export async function fetchLendingsStatistics() {
    const url = route('api.library.lending.stats')
    const res = await axios.get(url)
    return res.data
}

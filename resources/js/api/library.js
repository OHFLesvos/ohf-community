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

// Lending

export async function fetchLendingsStatistics() {
    const url = route('api.library.lending.stats')
    const res = await axios.get(url)
    return res.data
}

// Lending (from person)

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

export async function extendLendingToPerson(bookId, personId, days) {
    const url = route('api.library.lending.extendBookToPerson', [personId])
    const res = await axios.post(url, {
        book_id: bookId,
        days: days
    })
    return res.data
}

export async function returnBookFromPerson(bookId, personId) {
    const url = route('api.library.lending.returnBookFromPerson', [personId])
    const res = await axios.post(url, {
        book_id: bookId,
    })
    return res.data
}

export async function personLendingLog(id) {
    const url = route('api.library.lending.personLog', [id])
    const res = await axios.get(url)
    return res.data
}

// Lending (from book)

export async function findLendingOfBook(id) {
    const url = route('api.library.lending.book', [id])
    const res = await axios.get(url)
    return res.data
}

export async function lendBook(bookId, personId) {
    const url = route('api.library.lending.lendBook', [bookId])
    const res = await axios.post(url, {
        person_id: personId
    })
    return res.data
}

export async function extendLending(id, days) {
    const url = route('api.library.lending.extendBook', [id])
    const res = await axios.post(url, {
        days: days
    })
    return res.data
}

export async function returnBook(id) {
    const url = route('api.library.lending.returnBook', [id])
    const res = await axios.post(url)
    return res.data
}

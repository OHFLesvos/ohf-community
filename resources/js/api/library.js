import { api, route } from '@/api/baseApi'
export default {
    //
    // Books
    //
    async findIsbn (isbn) {
        const url = route('api.library.books.findIsbn', {
            isbn: isbn
        })
        return await api.get(url)
    },
    async listBooks (params) {
        const url = route('api.library.books.index', params)
        return await api.get(url)
    },
    async storeBook (data) {
        const url = route('api.library.books.store')
        return await api.post(url, data)
    },
    async findBook (id) {
        const url = route('api.library.books.show', id)
        return await api.get(url)
    },
    async updateBook (id, data) {
        const url = route('api.library.books.update', id)
        return await api.put(url, data)
    },
    async deleteBook (id) {
        const url = route('api.library.books.destroy', id)
        return await api.delete(url)
    },
    //
    // Lending
    //
    async fetchLendingsStatistics () {
        const url = route('api.library.lending.stats')
        return await api.get(url)
    },
    async listBorrowers () {
        const url = route('api.library.lending.persons')
        return await api.get(url)
    },
    async listLentBooks () {
        const url = route('api.library.lending.books')
        return await api.get(url)
    },
    //
    // Lending (from person)
    //
    async findLendingsOfPerson (id) {
        const url = route('api.library.lending.person', id)
        return await api.get(url)
    },
    async fetchPersonLog (personId) {
        const url = route('api.library.lending.personLog', personId)
        return await api.get(url)
    },
    async lendBookToPerson(book, personId) {
        const url = route('api.library.lending.lendBookToPerson', personId)
        let data
        if (typeof book == 'object') {
            data = book
        } else {
            data = {
                book_id: book
            }
        }
        return await api.post(url, data)
    },
    async extendLendingToPerson(bookId, personId, days) {
        const url = route('api.library.lending.extendBookToPerson', personId)
        return await api.post(url, {
            book_id: bookId,
            days: days
        })
    },
    async returnBookFromPerson(bookId, personId) {
        const url = route('api.library.lending.returnBookFromPerson', personId)
        return await api.post(url, {
            book_id: bookId,
        })
    },
    //
    // Lending (from book)
    //
    async findLendingOfBook(id) {
        const url = route('api.library.lending.book', id)
        return await api.get(url)
    },
    async fetchBookLog (bookId) {
        const url = route('api.library.lending.bookLog', bookId)
        return await api.get(url)
    },
    async lendBook(bookId, personId) {
        const url = route('api.library.lending.lendBook', bookId)
        return await api.post(url, {
            person_id: personId
        })
    },
    async extendLending(id, days) {
        const url = route('api.library.lending.extendBook', id)
        return await api.post(url, {
            days: days
        })
    },
    async returnBook(id) {
        const url = route('api.library.lending.returnBook', id)
        return await api.post(url)
    }
}

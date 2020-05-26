import { api, route, getAjaxErrorMessage } from '@/api/baseApi'
export default {
    async findCard (code) {
        const params = {
            code: code
        }
        const url = route('api.shop.cards.searchByCode', params)
        try {
            return await api.getNoCatch(url)
        } catch (err) {
            if (!err.response || err.response.status != 404) {
                console.error(err)
                throw getAjaxErrorMessage(err)
            }
        }
    },
    async redeemCard (id) {
        const url = route('api.shop.cards.redeem', id)
        return await api.patch(url)
    },
    async cancelCard (id) {
        const url = route('api.shop.cards.cancel', id)
        return await api.delete(url)
    },
    async listRedeemedToday () {
        const url = route('api.shop.cards.listRedeemedToday')
        return await api.get(url)
    },
    async listNonRedeemedByDay () {
        const url = route('api.shop.cards.listNonRedeemedByDay')
        return await api.get(url)
    },
    async deleteNonRedeemedByDay (date) {
        const params = {
            date: date
        }
        const url = route('api.shop.cards.deleteNonRedeemedByDay')
        return await api.post(url, params)
    }
}

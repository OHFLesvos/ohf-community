import axios from '@/plugins/axios'
import ziggyMixin from '@/mixins/ziggyMixin'
export const route = ziggyMixin.methods.route

import { getAjaxErrorMessage } from '@/utils'
export const api = {
    async get (url) {
        try {
            const res = await axios.get(url)
            return res.data
        } catch (err) {
            throw getAjaxErrorMessage(err)
        }
    },
    async post (url, data) {
        try {
            const res = await axios.post(url, data)
            return res.data
        } catch (err) {
            throw getAjaxErrorMessage(err)
        }
    },
    async put (url, data) {
        try {
            const res = await axios.put(url, data)
            return res.data
        } catch (err) {
            throw getAjaxErrorMessage(err)
        }
    },
    async delete (url) {
        try {
            const res = await axios.delete(url)
            return res.data
        } catch (err) {
            throw getAjaxErrorMessage(err)
        }
    }
}

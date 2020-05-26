import axios from '@/plugins/axios'
import ziggyMixin from '@/mixins/ziggyMixin'
export const route = ziggyMixin.methods.route

export const getAjaxErrorMessage = function (err) {
	var msg;
	if (err.response) {
		if (err.response.data.message) {
			msg = err.response.data.message;
		}
		if (err.response.data.errors) {
			msg += "\n" + Object.entries(err.response.data.errors).map(([k, v]) => {
				return v.join('. ');
			});
		} else if (err.response.data.error) {
			msg = err.response.data.error;
		}
		if (!msg) {
			msg = `Error ${err.response.status}: ${err.response.statusText}`
		}
	} else {
		msg = err
	}
	return msg;
}

const handleError = function(err) {
    console.error(err)
    throw getAjaxErrorMessage(err)
}

export const api = {
    async getNoCatch (url) {
        const res = await axios.get(url)
        return res.data
    },
    async get (url) {
        try {
            const res = await axios.get(url)
            return res.data
        } catch (err) {
            handleError(err)
        }
    },
    async post (url, data) {
        try {
            const res = await axios.post(url, data)
            return res.data
        } catch (err) {
            handleError(err)
        }
    },
    async postFormData (url, formData) {
        try {
            const res = await axios.post(url, formData, {
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
            })
            return res.data
        } catch (err) {
            handleError(err)
        }
    },
    async put (url, data) {
        try {
            const res = await axios.put(url, data)
            return res.data
        } catch (err) {
            handleError(err)
        }
    },
    async patch (url, data) {
        try {
            const res = await axios.patch(url, data)
            return res.data
        } catch (err) {
            handleError(err)
        }
    },
    async delete (url) {
        try {
            const res = await axios.delete(url)
            return res.data
        } catch (err) {
            handleError(err)
        }
    }
}

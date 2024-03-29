import axios from "@/plugins/axios";
import ziggyMixin from "@/mixins/ziggyMixin";
export const route = ziggyMixin.methods.route;

export const getAjaxErrorMessage = function(err) {
    if (err.response) {
        let msg = parseErrorResponse(err.response.data);
        if (!msg) {
            return `Error ${err.response.status}: ${err.response.statusText}`;
        }
        return msg
    }
    return err
};

const parseErrorResponse =  function(data) {
    let msg
    if (data.message) {
        msg = data.message;
    }
    if (data.errors) {
        msg += "\n" +
            Object.entries(data.errors)
                .map(([, v]) => v.filter(e => !msg.includes(e)).join(", "))
                .join(' ');
    } else if (data.error) {
        msg = data.error;
    }
    return msg
}

const parseBlobDataJson = async function(data) {
    if (data instanceof Blob && data.type == 'application/json') {
        return JSON.parse(await data.text());
    }
    return data
}

const handleError = function(err) {
    console.error(err);
    throw getAjaxErrorMessage(err);
};

export const api = {
    async getNoCatch(url) {
        const res = await axios.get(url);
        return res.data;
    },
    async get(url) {
        try {
            const res = await axios.get(url);
            return res.data;
        } catch (err) {
            handleError(err);
        }
    },
    async post(url, data) {
        try {
            const res = await axios.post(url, data);
            return res.data;
        } catch (err) {
            handleError(err);
        }
    },
    async postFormData(url, formData) {
        try {
            const res = await axios.post(url, formData, {
                headers: {
                    "Content-Type": "multipart/form-data"
                }
            });
            return res.data;
        } catch (err) {
            handleError(err);
        }
    },
    async put(url, data) {
        try {
            const res = await axios.put(url, data);
            return res.data;
        } catch (err) {
            handleError(err);
        }
    },
    async patch(url, data) {
        try {
            const res = await axios.patch(url, data);
            return res.data;
        } catch (err) {
            handleError(err);
        }
    },
    async delete(url) {
        try {
            const res = await axios.delete(url);
            return res.data;
        } catch (err) {
            handleError(err);
        }
    },
    async download(downloadUrl, method="GET", formData) {
        try {
            let response = await axios({
                url: downloadUrl,
                method: method,
                responseType: "blob",
                data: formData
            });

            let filename = null;
            const disposition = response.headers["content-disposition"];
            if (disposition && disposition.indexOf("attachment") !== -1) {
                var filenameRegex = /filename\*?=['"]?(?:UTF-\d['"]*)?([^;\r\n"']*)['"]?;?/;
                var matches = filenameRegex.exec(disposition);
                if (matches != null && matches[1]) {
                    filename = decodeURIComponent(matches[1].replace(/['"]/g, ""));
                }
            }

            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute("download", filename);
            document.body.appendChild(link);
            link.click();
        } catch (err) {
            handleError(err.response ? parseErrorResponse(await parseBlobDataJson(err.response.data)) : err);
        }
    }
};

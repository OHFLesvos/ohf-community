import axios from '@/plugins/axios'
import ziggyMixin from '@/mixins/ziggyMixin'
const route = ziggyMixin.methods.route

export async function findPerson(id) {
    const url = route('api.people.show', [id])
    const res = await axios.get(url)
    return res.data
}

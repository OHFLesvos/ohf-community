<template>
    <div v-if="lendings">
        <div v-if="lendings.length > 0" class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>{{ $t('library.book') }}</th>
                        <th>{{ $t('library.lent') }}</th>
                        <th>{{ $t('library.returned') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="lending in lendings" :key="lending.id">
                        <td>
                            <a :href="route('library.lending.book', [lending.book.id])">
                                {{ lending.book.title }}
                            </a>
                        </td>
                        <td>
                            {{ moment(lending.lending_date).format('LL') }}
                        </td>
                        <td>
                            <template v-if="lending.returned_date">
                                {{ moment(lending.returned_date).format('LL') }}
                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <b-alert
            v-else
            variant="info"
        >
            {{ $t('library.no_books_lent_so_far') }}
        </b-alert>
    </div>
    <p v-else>
        {{ $t('app.loading') }}
    </p>
</template>

<script>
import moment from 'moment'
import { personLendingLog } from '@/api/library'
export default {
    props: {
        personId: {
            required: true,
        }
    },
    data () {
        return {
            lendings: null
        }
    },
    methods: {
        moment
    },
    created () {
        personLendingLog(this.personId)
            .then(data => {
                this.lendings = data.data
            })
            .catch(err => console.error(err))
    }
}
</script>

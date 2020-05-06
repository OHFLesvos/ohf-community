<template>
    <div v-if="person">
        <h2 class="mb-3">
            {{ person.full_name }}
            <small class="d-block d-sm-inline">
                {{ person.nationality }}<template v-if="person.nationality && person.date_of_birth">,</template>
                {{ moment(person.date_of_birth).format("LL") }}
            </small>
        </h2>
        <template v-if="lendings">
            <div
                v-if="lendings.length > 0"
                class="table-responsive"
            >
                <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>{{ $t('library.book') }}</th>
                            <th class="d-none d-sm-table-cell">{{ $t('library.lent') }}</th>
                            <th>{{ $t('library.return') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="lending in lendings"
                            :key="lending.id"
                            :class="{
                                'table-danger': isOverdue(lending),
                                'table-warning': isSoonOverdue(lending)
                            }">
                            <td class="align-middle">
                                <a :href="route('library.lending.book', [lending.book.id])">
                                    {{ lending.book.title }}
                                    <template v-if="lending.book.author">
                                        ({{ lending.book.author }})
                                    </template>
                                </a>
                            </td>
                            <td class="align-middle d-none d-sm-table-cell">
                                {{ moment(lending.lending_date).format("LL") }}
                            </td>
                            <td class="align-middle">
                                {{ moment(lending.return_date).format("LL") }}
                            </td>
                            <td class="fit">
                                <form
                                    :action="route('library.lending.returnBookFromPerson', [person.id])"
                                    method="post"
                                    class="d-inline"
                                >
                                    <!-- {{ csrf_field() }} -->
                                    <!-- {{ Form::hidden('book_id', $lending->book->id) }} -->
                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-success"
                                    >
                                        <font-awesome-icon icon="inbox" />
                                        <span class="d-none d-sm-inline"> {{ $t('library.return') }}</span>
                                    </button>
                                </form>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary extend-lending-button"
                                    :data-book="lending.book.id"
                                >
                                    <font-awesome-icon icon="calendar-plus-o" />
                                    <span class="d-none d-sm-inline"> {{ $t('library.extend') }}</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <b-alert
                v-else
                show
            >
                {{ $t('library.no_books_lent') }}
            </b-alert>
        </template>
        <p v-if="canLend">
            <button
                type="button"
                class="btn btn-primary"
                data-toggle="modal"
                data-target="#lendBookModal"
            >
                <font-awesome-icon icon="plus-circle" />
                {{ $t('library.lend_a_book') }}
            </button>
        </p>
    </div>
    <p v-else>
        {{ $t('app.loading') }}
    </p>
</template>

<script>
import axios from '@/plugins/axios'
import moment from 'moment'
export default {
    props: {
        personId: {
            required: true
        }
    },
    data () {
        return {
            person: null,
            lendings: null,
            canLend: false
        }
    },
    created () {
        axios.get(this.route('api.people.show', [this.personId]))
            .then(res => {
                this.person = res.data.data
            })
            .catch(err => console.error(err))
        axios.get(this.route('api.library.lending.person', [this.personId]))
            .then(res => {
                this.lendings = res.data.data
                this.canLend = res.data.meta.can_lend
            })
            .catch(err => console.error(err))
    },
    methods: {
        moment,
        isOverdue (lending) {
            return moment(lending.return_date).isBefore(moment(), 'day')
        },
        isSoonOverdue (lending) {
            return moment(lending.return_date).isSame(moment(), 'day')
        }
    }
}
</script>

<template>
    <div
        v-if="lendings.length > 0"
        class="table-responsive"
    >
        <table class="table table-hover shadow-sm bg-white">
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
                        <b-link :to="{ name: 'library.lending.book', params: { bookId: lending.book.id }}">
                            {{ lending.book.title }}
                            <template v-if="lending.book.author">
                                ({{ lending.book.author }})
                            </template>
                        </b-link>
                    </td>
                    <td class="align-middle d-none d-sm-table-cell">
                        {{ moment(lending.lending_date).format("LL") }}
                    </td>
                    <td class="align-middle">
                        {{ moment(lending.return_date).format("LL") }}
                    </td>
                    <td class="fit align-middle">

                        <!-- Return book -->
                        <b-button
                            variant="success"
                            size="sm"
                            :disabled="disabled"
                            @click="$emit('return', lending.book.id)"
                        >
                            <font-awesome-icon icon="inbox" />
                            <span class="d-none d-sm-inline"> {{ $t('library.return') }}</span>
                        </b-button>

                        <!-- Extend lending  -->
                        <b-button
                            variant="primary"
                            size="sm"
                            :disabled="disabled"
                            @click="$emit('extend', lending.book.id)"
                        >
                            <font-awesome-icon icon="calendar-plus" />
                            <span class="d-none d-sm-inline"> {{ $t('library.extend') }}</span>
                        </b-button>

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

<script>
import moment from 'moment'
export default {
    props: {
        lendings: {
            type: Array,
            required: true
        },
        disabled: Boolean
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

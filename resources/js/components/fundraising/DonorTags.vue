<template>
    <div>
        <span
            v-if="error"
            class="text-danger"
        >
            {{ error }}
        </span>
        <span v-else-if="!loaded">
            <font-awesome-icon
                icon="spinner"
                spin
            />
            {{ $t('app.loading') }}
        </span>
        <tag-editor
            v-else-if="editor"
            :tags="tagNames"
            :disabled="busy"
            :suggestionsUrl="apiSuggestionsUrl"
            @cancel="editor = false"
            @submit="storeTags"
        />
        <template v-else>
            <a
                v-for="tag in tags"
                :key="tag.slug"
                :href="url(tag)"
                class="btn btn-link btn-sm">{{ tag.name }}</a>
            <b-button
                v-if="apiStoreUrl"
                variant="primary"
                size="sm"
                key="edit"
                @click="editor = true"
            >
                <font-awesome-icon :icon="editIcon" />
            </b-button>
        </template>
    </div>
</template>

<script>
import _ from 'lodash'
import axios from '@/plugins/axios'
import { BButton } from 'bootstrap-vue'
import TagEditor from './TagEditor'
import { handleAjaxError, getAjaxErrorMessage, showSnackbar } from '@/utils'
export default {
    components: {
        BButton,
        TagEditor
    },
    props: {
        apiListUrl: {
            required: true,
            type: String
        },
        apiStoreUrl: {
            required: false,
            type: String
        },
        apiSuggestionsUrl: {
            required: false,
            type: String
        },
        tagUrl: {
            required: false,
            type: String
        }
    },
    data () {
        return {
            tags: [],
            error: null,
            loaded: false,
            busy: false,
            editor: false
        }
    },
    mounted () {
        this.loadTags()
    },
    computed: {
        editIcon () {
            return this.tags.length > 0 ? 'pencil-alt' : 'plus-circle'
        },
        tagNames () {
            return this.tags.map(t => t.name)
        }
    },
    methods: {
        loadTags () {
            this.error = null
            axios.get(this.apiListUrl)
                .then((res) => {
                    this.tags = res.data.data
                    this.loaded = true
                })
                .catch(err => this.error = getAjaxErrorMessage(err))
        },
        storeTags (tags) {
            if (_.isEqual(this.tagNames, tags)) {
                this.editor = false
                return
            }
            this.error = null
            this.busy = true
            axios.post(this.apiStoreUrl, {
                    tags: tags
                })
                .then((res) => {
                    this.tags = res.data.data
                    this.editor = false
                    showSnackbar(res.data.message)
                })
                .catch(handleAjaxError)
                .finally(() => this.busy = false)
        },
        url (tag) {
            if (this.tagUrl) {
                return this.tagUrl + tag.slug
            }
            return null
        }
    }
}
</script>

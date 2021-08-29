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
            {{ $t('Loading...') }}
        </span>
        <tag-editor
            v-else-if="editor"
            :tags="tagNames"
            :disabled="busy"
            :suggestions-provider="suggestionsProvider"
            :preload="preload"
            @cancel="editor = false"
            @submit="storeTags"
        />
        <template v-else>
            <template v-for="(tag, idx) in tags">
                <b-button
                    :key="tag.slug"
                    variant="secondary"
                    size="sm"
                    @click="$emit('tag-click', tag.slug)"
                >
                    {{ tag.name }}
                </b-button><template v-if="idx + 1 < tags.length">{{ ' ' }}</template>
            </template>
            <b-button
                v-if="storeProvider"
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
import TagEditor from '@/components/tags/TagEditor'
import { showSnackbar } from '@/utils'
export default {
    components: {
        TagEditor
    },
    props: {
        listProvider: {
            required: true,
            type: Function
        },
        storeProvider: {
            required: false,
            type: Function
        },
        suggestionsProvider: {
            required: false,
            type: Function
        },
        preload: Boolean
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
        async loadTags () {
            this.error = null
            try {
                let data = await this.listProvider()
                this.tags = data.data
                this.loaded = true
            } catch (err) {
                this.error = err
            }
        },
        async storeTags (tags) {
            if (_.isEqual(this.tagNames, tags)) {
                this.editor = false
                return
            }
            this.error = null
            this.busy = true
            try {
                let data = await this.storeProvider({ tags: tags })
                this.tags = data.data
                this.editor = false
                showSnackbar(data.message)
            } catch(err) {
                alert(err)
            }
            this.busy = false
        }
    }
}
</script>

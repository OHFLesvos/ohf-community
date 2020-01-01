<template>
    <span class="form-inline d-inline">
        <icon name="spinner" :spin="true" v-if="busy"></icon>
        <template v-else-if="dateOfBirth != null">{{ dateOfBirth }}  (age {{ age }})</template>
        <template v-else-if="canUpdate">
            <template v-if="form">
                <input
                    type="text"
                    :max="todayDate"
                    pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}'"
                    title="YYYY-MM-DD"
                    placeholder="YYYY-MM-DD"
                    v-model="newDateOfBirth"
                    class="form-control form-control-sm"
                    ref="input"
                    @keydown.enter="setDateOfBirth"
                    @keydown.esc="form = false"
                />
                <button type="button" class="btn btn-primary btn-sm" @click="setDateOfBirth">
                    <icon name="check"></icon>
                </button>
                <button type="button" class="btn btn-secondary btn-sm" @click="form = false">
                    <icon name="times"></icon>
                </button>
            </template>
            <button class="btn btn-warning btn-sm" title="Set date of birth" v-else @click="form = true">
                <icon name="calendar-plus"></icon>
            </button>
        </template>
    </span>
</template>

<script>
    import Icon from '@app/components/Icon'
    import showSnackbar from '@app/snackbar'
    import { handleAjaxError } from '@app/utils'
    export default {
        components: {
            Icon
        },
        props: {
            updateUrl: {
                type: String,
                required: true
            },
            value: {
                type: String,
                required: false,
                defaul: null
            },
            canUpdate: Boolean
        },
        data() {
            return {
                busy: false,
                form: false,
                dateOfBirth: this.value != null && this.value.length > 0 ? this.value : null,
                newDateOfBirth: null
            }
        },
        computed: {
            age() {
                var today = new Date();
                var birthDate = new Date(this.dateOfBirth);
                var age = today.getFullYear() - birthDate.getFullYear();
                var m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                return age;
            },
            todayDate() {
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth() + 1; //January is 0!

                var yyyy = today.getFullYear();
                if(dd < 10) {
                    dd='0' + dd;
                }
                if(mm < 10) {
                    mm='0' + mm;
                }
                return yyyy + '-' + mm + '-' + dd;
            }
        },
        methods: {
            setDateOfBirth() {
                if (this.newDateOfBirth == null || !this.newDateOfBirth.match('^[0-9]{4}-[0-9]{2}-[0-9]{2}$')) {
                    this.$refs.input.select();
                    return;
                }
                this.busy = true
                axios.patch(this.updateUrl, {
                        'date_of_birth': this.newDateOfBirth
                    })
                    .then(response => {
                        var data = response.data
                        this.dateOfBirth = this.newDateOfBirth
                        this.form = false
                        showSnackbar(data.message);
                        // enableFilterSelect(); // TODO
                        // Remove buttons not maching age-restrictions
                        // $('button[data-min_age]').each(() => {
                        //     if ($(this).data('min_age') && data.age < $(this).data('min_age')) {
                        //         $(this).parent().remove();
                        //     }
                        // });
                        // $('button[data-max_age]').each(() => {
                        //     if ($(this).data('max_age') && data.age > $(this).data('max_age')) {
                        //         $(this).parent().remove();
                        //     }
                        // });
                    })
                    .catch(err => {
                        handleAjaxError(err);
                        this.busy = false
                        this.$nextTick(() => {
                            this.$refs.input.select();
                        })
                    })
                    .then(() => {
                        this.busy = false
                    })
            },
        },
        watch: {
            form(val, oldVal) {
                if (val && !oldVal) {
                    this.$nextTick(() => {
                        this.$refs.input.focus();
                    })
                }
                if (!val && oldVal) {
                    this.newDateOfBirth = null
                }
            }
        }
    }
</script>
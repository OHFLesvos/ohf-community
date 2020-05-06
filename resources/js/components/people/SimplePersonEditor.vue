<template>
    <div>
        <div class="form-row">

            <!-- Name -->
            <div class="col-md">
                <b-form-group>
                    <b-form-input
                        v-model.trim="person.name"
                        :placeholder="$t('people.name')"
                        autocomplete="off"
                        required
                        ref="focusThis"
                    />
                </b-form-group>
            </div>

            <!-- Family Name -->
            <div class="col-md">
                <b-form-group>
                    <b-form-input
                        v-model.trim="person.family_name"
                        autocomplete="off"
                        required
                        :placeholder="$t('people.family_name')"
                    />
                </b-form-group>
            </div>

        </div>
        <div class="form-row">

            <!-- Gender -->
            <div class="col-md">
                <gender-radio-input
                    v-model="person.gender"
                    hide-label
                />
            </div>

            <!-- Date of birth -->
            <div class="col-md">
                <b-form-group>
                    <b-input-group
                        :append="$t('people.age_n', {age: age != null ? age : '...'})"
                    >
                        <b-form-input
                            v-model.trim="person.date_of_birth"
                            :placeholder="$t('people.date_of_birth')"
                            required
                        />
                    </b-input-group>
                </b-form-group>
            </div>
        </div>

        <div class="form-row">

            <!-- Nationality -->
            <div class="col-md">
                <b-form-group>
                    <b-form-input
                        v-model.trim="person.nationality"
                        :placeholder="$t('people.nationality')"
                        list="country-list"
                    />
                    <b-form-datalist
                        id="country-list"
                        :options="countries"
                    />
                </b-form-group>
            </div>

            <!-- Police number -->
            <div class="col-md">
                <b-form-group>
                    <b-input-group prepend="05/">
                        <b-form-input
                            v-model.number="person.police_no"
                            type="number"
                            autocomplete="off"
                            no-wheel
                            :placeholder="$t('people.police_number')"
                        />
                    </b-input-group>
                </b-form-group>
                <!-- TODO auto fill zeros -->
            </div>

        </div>
    </div>
</template>

<script>
import { isDateString, dateOfBirthToAge } from '@/utils'
import axios from '@/plugins/axios'
import GenderRadioInput from '@/components/people/GenderRadioInput'
export default {
    components: {
        GenderRadioInput,
    },
    props: {
        value: {
            type: Object,
            required: true
        }
    },
    data () {
        const val = this.value
        return {
            person: {
                name: val.name ? val.name : '',
                family_name: val.family_name ? val.family_name : '',
                gender: val.gender ? val.gender : '',
                date_of_birth: val.date_of_birth ? val.date_of_birth : '',
                nationality: val.nationality ? val.nationality : '',
                police_no: val.police_no ? val.police_no : ''
            },
            countries: []
        }
    },
    computed: {
        age () {
            if (isDateString(this.person.date_of_birth)) {
                return dateOfBirthToAge(this.person.date_of_birth)
            }
            return null
        }
    },
    watch: {
        person: {
            deep: true,
            handler(val) {
                this.$emit('input', val)
            }
        }
    },
    methods: {
        focus () {
            this.$refs.focusThis.focus()
        }
    },
    created() {
        axios.get(this.route('api.countries'))
            .then(res => this.countries = Object.values(res.data))
    }
}
</script>

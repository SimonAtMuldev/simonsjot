<template>
    <div>
        <form @submit.prevent="submitForm">
            <InputField name="name"   type="text" label="Contact Name"
                        placeholder="Contact Name" @update:field="form.name = $event"  :errors="errors"/>

            <InputField name="email"  type="text" label="E-Mail"
                        placeholder="your@email" @update:field="form.email = $event" :errors="errors"/>

            <InputField name="company" type="text" label="Company Name"
                        placeholder="Company Name" @update:field="form.company = $event" :errors="errors"/>

            <div class="w-full text-gray-900 border-b pb-2 mb-20 hover:border-blue-400">
                <label for="dob" class="contact_create_label border-none">Date of Birth</label>
                <input id="dob" type="date" v-model="form.dob"
                       class="pt-8 focus:outline-none w-48" @update:field="form.dob = $event"
                />
            </div>

            <!-- Buttons -->
            <div class="flex justify-end">
                <button class="py-2 px-4 rounded text-red-700 border mr-5 hover:border-red-700">Cancel</button>
                <button class="bg-blue-500 py-2 px-4 rounded text-white hover:bg-blue-400"
                        >Add New Contact</button>
            </div>

        </form>
    </div>
</template>


<script>
    import InputField from "../components/InputField";
    import moment from 'moment';

    export default {
        name: "ContactsCreate",
        components: {InputField},

        data: function () {
            return {
                form: {
                    'name': '',
                    'email': '',
                    'company': '',
                    'dob': moment().format('YYYY-MM-DD'),
                },
                errors: null,
                test: ''
            }
        },

        filters: {
            capitalize: function(value) {
                return value.replace(/(?:^|\s|-)\S/g, x => x.toUpperCase());
            }
        },

        methods: {
            submitForm: function () {
                axios.post('/api/contacts', this.form)
                .then(response => {
                    this.$router.push(response.data.links.self);
                })
                .catch(errors => {
                    this.errors = errors.response.data.errors
                });
            },
        }


    }


</script>

<style scoped>

</style>

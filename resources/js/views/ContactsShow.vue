<template>
    <div v-if="loading" class="w-full h-screen"><img src="/img/loading2.gif" alt="Loading"></div>
    <div v-else>
        <!-- Top Links -->
        <div class="flex justify-between">
            <!-- Left Section -->
            <a href="#" class="text-blue-400" @click="$router.back()">
                < Back
            </a>
                <!-- Right Section -->
                <div class="relative">
                    <router-link :to="'/contacts/' + contact.contact_id + '/edit'"
                                  class="px-4 py-2 rounded text-sm text-green-500 border border-green-500 text-sm font-bold mr-2"
                    >Edit</router-link>

                    <!-- set the modal boolean value to the opposite of it's current state -->
                    <a href="#" @click="modal = ! modal"
                       class="px-4 py-2 rounded text-sm text-red-500 border border-red-500 text-sm font-bold">Delete</a>
                    <!-- Only show model when model is true -->
                    <div v-if="modal"
                         class="absolute z-20 right-0 bg-blue-900 text-white rounded-lg p-8 lg:w-96 mt-2 mr-6">
                        <p>Are you sure you want to delete this record?</p>

                        <div class="flex items-center mt-6 justify-end">
                            <!-- Hide the modal (Opposite Value = false)-->
                            <button class="text-white pr-4" @click="modal = ! modal">Cancel</button>
                            <button @click="destroy"
                                class="px-4 py-2 bg-red-500 rounded text-white text-sm font-bold">Delete</button>
                        </div>
                    </div>


                </div>
            <div v-if="modal" @click="modal = ! modal"
                 class="bg-black opacity-50 absolute right-0 left-0 top-0 right-0 h-screen"></div>
            </div>



        <div class="flex items-center pt-6">

            <UserCircle :name="contact.name" />

            <p class="pl-5 text-xl">{{ contact.name}}</p>
        </div>
        <p class="pt-6 text-gray-600 font-bold uppercase text-sm">Contact</p>
            <p class="pt-2 text-gray-400">{{ contact.email }}</p>
        <p class="pt-6 text-gray-600 font-bold uppercase text-sm">Company</p>
            <p class="pt-2 text-gray-400">{{ contact.company }}</p>
        <p class="pt-6 text-gray-600 font-bold uppercase text-sm">Birthday</p>
            <p class="pt-2 text-gray-400">{{ contact.dob }}</p>
    </div>
</template>

<script>
    import UserCircle from '../components/UserCircle';

    export default {
        name: "ContactsShow",

        props: ['items'],

        components: {
            UserCircle
        },

        data: function () {
            return {
                loading: true,
                modal: false,
                contact: '',
            }
        },

        mounted() {
            axios.get('/api/contacts/' + this.$route.params.id)
            .then(response => {
                this.contact = response.data.data;
                this.loading = false;

            })
            .catch(error => {
                this.loading = false;

                if (error.response.status === 404) {
                    this.$router.push('/contacts');
                }
            });
        },

        methods: {
            destroy: function () {
                axios.delete('/api/contacts/' + this.$route.params.id)
                .then(response => { this.$router.push('/contacts');})

                .catch(error => { alert('Interenal error. Unable to delete contacts.');});
            }
        }
    }
</script>

<style scoped>

</style>

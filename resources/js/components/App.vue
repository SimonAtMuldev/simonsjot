<template>
    <div class="h-screen bg-white">
        <div class="flex">
            <div class="bg-gray-200 w-48 h-screen border-r-2 border-gray-400">
                <nav class="pt-2 pl-2">
                    <router-link to="/">
                        <PicLogo />
                    </router-link>

                    <p class="pt-12 text-gray-500 uppercase font-bold">create</p>
                    <router-link to="/contacts/create" class="flex items-center py-2 hover:text-blue-600 text-sm">
                        <PicPlus />
                        <div class="tracking-wide pl-3">Add New</div>
                    </router-link>

                    <p class="pt-12 text-gray-500 uppercase font-bold">general</p>
                    <router-link to="/">
                        <router-link to="/" class="flex items-center py-2 hover:text-blue-600 text-sm">
                            <PicCalendar />
                            <div class="tracking-wide pl-3">Contacts</div>
                        </router-link>
                        <router-link to="/" class="flex items-center py-2 hover:text-blue-600 text-sm">
                            <PicCake />
                            <div class="tracking-wide pl-3">Birthdays</div>
                        </router-link>
                    </router-link>

                    <p class="pt-12 text-gray-500 uppercase font-bold">settings</p>
                    <router-link to="/">
                        <router-link to="/" class="flex items-center py-2 hover:text-blue-600 text-sm">
                            <pic-logout />
                            <div class="tracking-wide pl-3">Logout</div>
                        </router-link>
                    </router-link>
                </nav>


            </div>

            <div class="flex flex-col flex-1 h-screen overflow-y-hidden">

                <div class="h-16 px-6 border-b border-gray-400 flex items-center justify-between">
                    <div>
                        Contacts
                    </div>

                    <UserCircle :name="user.name" />

                </div>

                <!-- Trick: Scrollbar inside div
                        parent <div> has overflow-y-hidden
                        child <div> has overflow-x-hidden
                 -->
                <div class="flex flex-1 overflow-y-hidden flex-col">
                    <router-view class="p-6 overflow-x-hidden"></router-view>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import PicLogo from './PicLogo'
    import PicCalendar from "./PicCalendar";
    import PicLogout from "./PicLogout";
    import PicCake from "./PicCake";
    import PicPlus from "./PicPlus";
    import UserCircle from './UserCircle';


    export default {
        name: "App",
        components: { PicLogo, PicCalendar, PicLogout, PicCake, PicPlus, UserCircle },
        comments: {
            PicLogo
        },
        props: [
          'user'
        ],

        created() {
            window.axios.interceptors.request.use(
                (config) => {
                    if(config.method === 'get') {
                        config.url = config.url + '?api_token=' + this.user.api_token;
                    } else {
                        config.data = {
                            ...config.data,
                            api_token: this.user.api_token,
                        };
                    }

                    return config;
                }
            )
        }
    }

</script>

<style scoped>

</style>

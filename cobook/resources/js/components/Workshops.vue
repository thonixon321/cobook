<template>
    <Layout>
        <div class="workshops_container">
            <MapTool></MapTool>
            <button @click="logout">Logout</button>
        </div>
    </Layout>
</template>

<script>
import Layout from "../layouts/Layout.vue";
import MapTool from "./tools/MapTool.vue";
import { mapActions } from "vuex";

export default {
    name: "workshops",

    data() {
        return {};
    },

    computed: {},

    methods: {
        ...mapActions({
            signOut: "auth/logout",
            getWorkshops: "workshops",
        }),

        async logout() {
            await axios.post("/logout").then(({ data }) => {
                this.signOut();
                this.$router.push({ name: "login" });
            });
        },
    },

    mounted() {},

    created() {
        //get workshops
        this.getWorkshops();
    },

    components: {
        Layout,
        MapTool,
    },
};
</script>

<style>
div.workshops_container {
    width: 100%;
    postion: relative;
}
</style>

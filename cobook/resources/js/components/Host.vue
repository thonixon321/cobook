<template>
    <Layout>
        <div>
            <h2>Hosting</h2>
        </div>
    </Layout>
</template>

<script>
import Layout from "../layouts/Layout.vue";
import { mapActions } from "vuex";
export default {
    name: "Host",

    methods: {
        ...mapActions({
            activateLink: "activateLink",
        }),
    },

    mounted() {
        this.activateLink("host");
    },

    components: {
        Layout,
    },
};
</script>

<style></style>

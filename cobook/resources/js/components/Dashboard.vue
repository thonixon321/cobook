<template>
    <Layout>
        <div>
            <h2>Dashboard</h2>
        </div>
    </Layout>
</template>

<script>
import Layout from "../layouts/Layout.vue";
import { mapActions } from "vuex";
export default {
    name: "dashboard",

    data() {
        return {};
    },

    computed: {},

    methods: {
        ...mapActions({
            activateLink: "activateLink",
        }),
    },

    mounted() {
        this.activateLink("dashboard");
    },

    created() {
        //get workshops
        axios
            .get("/api/workshops")
            .then((response) => {
                this.workshops = response.data.data;
            })
            .catch((error) => {
                alert(error);
            });
    },

    components: {
        Layout,
    },
};
</script>

<template>
    <div>
        <h1>Dashboard Page</h1>
        <router-link :to="{ name: 'workshops' }">Workshops</router-link>
    </div>
</template>

<script>
export default {
    name: "dashboard",

    data() {
        return {};
    },

    computed: {},

    methods: {},

    mounted() {},

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
};
</script>

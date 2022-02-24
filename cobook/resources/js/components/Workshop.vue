<template>
    <Layout>
        <h2 class="page_title">Workshop</h2>
        <div class="workshop_container">
            <div class="workshop_card">
                <img
                    src="/images/joel-holland-unsplash.jpg"
                    alt="green landscape"
                    class="defaultImage"
                />
                <h2>{{ workshop.workshopName }}</h2>
            </div>
        </div>
    </Layout>
</template>

<script>
import Layout from "../layouts/Layout.vue";
import { mapActions } from "vuex";

export default {
    name: "Workshop",

    props: ["id"],

    data() {
        return {
            workshop: {},
        };
    },

    methods: {
        ...mapActions({
            activateLink: "activateLink",
        }),
    },

    mounted() {},

    created() {
        //test
        this.activateLink("workshops");
        axios
            .get("/api/workshops/" + this.id)
            .then((response) => {
                console.log({
                    data: response.data.data,
                });
                this.workshop = response.data.data;
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

<style scoped>
.workshop_container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.workshop_card {
    width: 80%;
    height: 30em;
    margin-top: 0.61em;
}

.defaultImage {
    width: 100%;
    height: 13em;
}
</style>

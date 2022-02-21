<template>
    <Layout>
        <div class="workshops_container">
            <h2>Workshops</h2>
            <div class="search_container">
                <p>Search By:</p>
                <div class="search_container--item">
                    <label>
                        <input
                            type="radio"
                            name="searchTypes"
                            value="address"
                            class="radioInput"
                            v-model="searchType"
                            checked
                        />
                        <span>Address</span>
                    </label>
                </div>
                <div class="search_container--item">
                    <label>
                        <input
                            type="radio"
                            name="searchTypes"
                            value="workshop"
                            class="radioInput"
                            v-model="searchType"
                        />
                        <span>Workshop</span>
                    </label>
                </div>
                <div class="search_container--item">
                    <label>
                        <input
                            type="radio"
                            name="searchTypes"
                            value="host"
                            class="radioInput"
                            v-model="searchType"
                        />
                        <span>Host</span>
                    </label>
                </div>
            </div>
            <MapTool></MapTool>
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
        return {
            searchType: "address",
        };
    },

    computed: {},

    methods: {
        ...mapActions({
            getWorkshops: "workshops",
            activateLink: "activateLink",
        }),
    },

    mounted() {},

    created() {
        //get workshops
        this.getWorkshops();
        this.activateLink("workshops");
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
    position: relative;
}

.search_container {
    display: flex;
    align-items: center;
}

.search_container--item {
    margin-left: 0.3em;
}

.radioInput {
    display: none;
}
</style>

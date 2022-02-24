<template>
    <Layout>
        <div class="workshops_container">
            <h2 class="page_title">Workshops</h2>
            <div class="search_container">
                <div class="searchTypes_container">
                    <div
                        class="searchSelectBox"
                        :class="{
                            middle: middlePosition,
                            right: rightPosition,
                        }"
                    ></div>

                    <p>Search By:</p>
                    <div class="search_container--item">
                        <label @click="changeSearchType('address')">
                            <input
                                type="radio"
                                name="searchTypes"
                                value="address"
                                class="radioInput"
                                v-model="searchType"
                                checked
                            />
                            <span
                                class="searchTypeLabel"
                                :class="{
                                    active: leftPosition,
                                }"
                                >Address</span
                            >
                        </label>
                    </div>
                    <div class="search_container--item">
                        <label @click="changeSearchType('workshop')">
                            <input
                                type="radio"
                                name="searchTypes"
                                value="workshopName"
                                class="radioInput"
                                v-model="searchType"
                            />
                            <span
                                class="searchTypeLabel"
                                :class="{
                                    active: middlePosition,
                                }"
                                >Workshop</span
                            >
                        </label>
                    </div>
                    <div class="search_container--item">
                        <label @click="changeSearchType('host')">
                            <input
                                type="radio"
                                name="searchTypes"
                                value="hostName"
                                class="radioInput"
                                v-model="searchType"
                            />
                            <span
                                class="searchTypeLabel"
                                :class="{
                                    active: rightPosition,
                                }"
                                >Host</span
                            >
                        </label>
                    </div>
                </div>
                <div class="searchInput_container">
                    <button @click="retrieveAllWorkshops" class="showAllBtn">
                        Show all workshops
                    </button>
                    <button @click="retrieveWorkshops" class="svgBox">
                        <svg
                            width="20"
                            height="20"
                            viewBox="0 0 5.2916664 5.2916668"
                            version="1.1"
                            id="svg1450"
                        >
                            <defs id="defs1447" />
                            <g
                                id="g6"
                                transform="matrix(0.00796144,0,0,0.00970479,0.75999644,0.39751585)"
                                style="fill: currentColor"
                            >
                                <g id="g4" style="fill: currentColor">
                                    <path
                                        d="M 481.8,453 341.8,312.9 C 369.4,279.8 386,237.5 386,191.3 386,85.9 299.5,0.2 193.1,0.2 86.7,0.2 0,86 0,191.4 c 0,105.4 86.5,191.1 192.9,191.1 45.2,0 86.8,-15.5 119.8,-41.4 l 140.5,140.5 c 8.2,8.2 20.4,8.2 28.6,0 8.2,-8.2 8.2,-20.4 0,-28.6 z M 41,191.4 c 0,-82.8 68.2,-150.1 151.9,-150.1 83.7,0 151.9,67.3 151.9,150.1 0,82.8 -68.2,150.1 -151.9,150.1 C 109.2,341.5 41,274.1 41,191.4 Z"
                                        id="path2-5"
                                        style="fill: currentColor"
                                    />
                                </g>
                            </g>
                        </svg>
                    </button>
                    <input
                        type="text"
                        v-model="searchText"
                        placeholder="search"
                    />
                </div>
            </div>
            <MapTool></MapTool>
            <h2 class="results_title">Results</h2>
            <div v-if="workshops.length" class="results_container">
                <div
                    class="results_container--item"
                    v-for="(result, index) in workshops"
                    :key="index"
                    @click="goToWorkshopPage(result)"
                >
                    <div class="first_info">
                        <div class="host_thumbnail">
                            <img v-if="thumbnail" :src="thumbnail" />
                            <div v-else>
                                {{
                                    result.workshopName.charAt(0).toUpperCase()
                                }}
                            </div>
                        </div>
                        <div class="resultInfo">
                            <p class="workshopName">
                                {{ result.workshopName }}
                            </p>
                            <p class="workshopAddressHost">
                                {{ result.location.address }} -
                                {{ result.name }}
                            </p>
                        </div>
                    </div>
                    <div class="last_info">
                        <p>{{ result.startDate }} - {{ result.endDate }}</p>
                        <p>0 Attendees</p>
                    </div>
                </div>
            </div>
            <div class="no-results" v-else>No Results Found</div>
        </div>
    </Layout>
</template>

<script>
import Layout from "../layouts/Layout.vue";
import MapTool from "./tools/MapTool.vue";
import { mapActions, mapGetters, mapState } from "vuex";

export default {
    name: "workshops",

    data() {
        return {
            searchType: "address",
            searchText: "",
            leftPosition: true,
            middlePosition: false,
            rightPosition: false,
        };
    },

    computed: {
        ...mapGetters({
            workshops: "getWorkshops",
            thumbnail: "auth/getThumbnail",
            locationOfUser: "auth/getUserLocation",
        }),
    },

    methods: {
        ...mapActions({
            getWorkshops: "workshops",
            activateLink: "activateLink",
            centerMap: "centerMap",
            userLocation: "auth/userLocation",
        }),

        changeSearchType(type) {
            if (type == "workshop") {
                this.leftPosition = false;
                this.middlePosition = true;
                this.rightPosition = false;
            } else if (type == "host") {
                this.leftPosition = false;
                this.middlePosition = false;
                this.rightPosition = true;
            } else {
                this.leftPosition = true;
                this.middlePosition = false;
                this.rightPosition = false;
            }
        },

        retrieveWorkshops() {
            let coordinates = {};
            if (this.searchText == "") {
                this.getWorkshops("");
                this.centerMap(this.locationOfUser);
            } else {
                if (this.searchType == "address") {
                    this.getWorkshops("?address=" + this.searchText);
                } else if (this.searchType == "workshopName") {
                    this.getWorkshops("?workshopName=" + this.searchText);
                } else {
                    this.getWorkshops("?hostName=" + this.searchText);
                }
                coordinates.lat = this.workshops[0].location.latitude;
                coordinates.lng = this.workshops[0].location.longitude;
                this.centerMap(coordinates);
            }
        },

        retrieveAllWorkshops() {
            this.getWorkshops("");
        },

        goToWorkshopPage(workshop) {
            router.push({
                path: "workshops/" + workshop.workshop_id,
            });
        },
    },

    mounted() {},

    created() {
        //get workshops
        this.getWorkshops("");
        this.activateLink("workshops");
        //get user's coordinates
        this.$getLocation({})
            .then((coordinates) => {
                this.centerMap(coordinates);
                this.userLocation(coordinates);
            })
            .catch((error) => alert(error));
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

.page_title {
    margin-bottom: 0.2em;
}

.search_container {
    display: flex;
    align-items: center;
    position: relative;
    margin-bottom: -0.38em;
    margin-right: 1em;
    justify-content: space-between;
}

.searchTypes_container {
    display: flex;
    align-items: center;
}

.searchSelectBox {
    position: absolute;
    top: 1.05em;
    left: 5.1em;
    width: 4.6em;
    height: 1.3em;
    background: #442178;
    border-radius: 5px;
    z-index: -1;
    transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.searchSelectBox.middle {
    transform: translateX(4.72em);
    width: 5.2em;
}

.searchSelectBox.right {
    width: 2.86em;
    transform: translateX(10.2em);
}

.search_container--item {
    margin-left: 0.83em;
}

.search_container--item label {
    cursor: pointer;
}

.radioInput {
    display: none;
}

.searchTypeLabel {
    color: grey;
}

.searchTypeLabel.active {
    color: white;
}

.searchInput_container {
    position: relative;
}

.svgBox {
    background: #442178;
    color: white;
    position: absolute;
    right: 0em;
    height: 2.2em;
    border: none;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
    cursor: pointer;
}

.searchInput_container input {
    height: 1.8em;
    width: 13em;
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
    border: 1.4px solid;
    margin-right: 2.2em;
}

.results_container {
    margin-top: -0.8em;
}

.results_container--item {
    height: 5em;
    background: #e4e2e2;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1em;
    font-size: 0.8em;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    border-radius: 2px;
}

.results_container--item:hover {
    transform: scale(1.01);
    box-shadow: 0px 3px 15px rgba(0, 0, 0, 0.2);
}

.results_container--item .first_info {
    display: flex;
    align-items: center;
}

.results_container--item p {
    margin: 0.1em;
}

.host_thumbnail {
    width: 2.63em;
    height: 2.63em;
    border-radius: 50%;
    background: #3f2519;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-left: 0.6em;
    margin-right: 0.4em;
}

.last_info {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-right: 1em;
}

.workshopName {
    font-size: 1.2em;
    font-weight: bolder;
}

.showAllBtn {
    text-decoration: underline;
    border: none;
    background: none;
    position: absolute;
    top: -1.65em;
    left: -0.3em;
    cursor: pointer;
}
</style>

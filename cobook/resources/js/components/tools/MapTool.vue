<template>
    <div class="map">
        <gmap-map
            :center="mapCenter"
            :zoom="7"
            style="width: 100%; height: 330px"
            ref="mapRef"
            @dragend="handleDrag"
        >
            <gmap-info-window
                :options="infoWindowOptions"
                :position="infoWindowPosition"
                :opened="infoWindowOpened"
                @closeclick="handleCloseClick"
            >
                <div class="info-window">
                    <h3>{{ activeWorkshop.workshopName }}</h3>
                    <p>{{ activeWorkshop.location.address }}</p>
                </div>
            </gmap-info-window>
            <gmap-marker
                v-for="(workshop, index) in workshops"
                :key="index"
                :position="getWorkshopPosition(workshop)"
                :clickable="true"
                :draggable="false"
                @click="handleMarkerClick(workshop)"
            >
            </gmap-marker>
        </gmap-map>
    </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
    name: "map-tool",

    data() {
        return {
            map: null,
            blueMark:
                "http://maps.google.com/mapfiles/kml/paddle/blu-circle.png",
            infoWindowOptions: {
                pixelOffset: {
                    width: 0,
                    height: -35,
                },
            },
            infoWindowPosition: {
                lat: 0,
                lng: 0,
            },
            activeWorkshop: {
                description: "",
                endDate: "",
                location: {
                    address: "",
                    latitude: "",
                    longitude: "",
                    name: "",
                },
                name: "",
                startDate: "",
                workshop_id: "",
            },
            infoWindowOpened: false,
            myInfoWindowOpened: false,
        };
    },

    computed: {
        ...mapGetters({
            workshops: "getWorkshops",
            mapCenter: "getMapCenter",
        }),

        mapCoordinates() {
            if (!this.map) {
                return {
                    lat: 0,
                    lng: 0,
                };
            }

            return {
                lat: this.map.getCenter().lat().toFixed(4),
                lng: this.map.getCenter().lng().toFixed(4),
            };
        },
    },

    methods: {
        ...mapActions({
            signOut: "auth/logout",
        }),

        handleDrag() {
            //get center and zoom level, store in local storage
            //more and more
        },

        getWorkshopPosition(workshop) {
            console.log({ center: this.mapCenter });
            console.log({
                lat: parseFloat(workshop.location.latitude),
                lng: parseFloat(workshop.location.longitude),
            });
            return {
                lat: parseFloat(workshop.location.latitude),
                lng: parseFloat(workshop.location.longitude),
            };
        },

        handleMarkerClick(workshop) {
            this.myInfoWindowOpened = false;
            this.activeWorkshop = workshop;
            this.infoWindowPosition = {
                lat: parseFloat(this.activeWorkshop.location.latitude),
                lng: parseFloat(this.activeWorkshop.location.longitude),
            };
            this.infoWindowOpened = true;
        },

        handleCloseClick() {
            this.myInfoWindowOpened = false;
            this.infoWindowOpened = false;
            this.activeWorkshop = {
                description: "",
                endDate: "",
                location: {
                    address: "",
                    latitude: "",
                    longitude: "",
                    name: "",
                },
                name: "",
                startDate: "",
                workshop_id: "",
            };
        },
    },

    mounted() {
        //add map to data obj
        this.$refs.mapRef.$mapPromise.then((map) => {
            console.log("Map:", map);
            this.map = map;
            return;
        });
    },

    created() {},
};
</script>
<style scoped>
.map {
    margin-right: 1em;
}
</style>

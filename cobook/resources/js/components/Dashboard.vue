<template>
    <div>
        <h2>Hello {{ user.name }}!</h2>

        <form
            action="javascript:void(0)"
            @submit="getLatLng"
            class="row"
            method="post"
        >
            <div class="form-group col-12">
                <label for="address" class="font-weight-bold"
                    >Get Lat and Lng for Address:</label
                >
                <input
                    type="text"
                    name="address"
                    v-model="form.address"
                    id="address"
                    placeholder="Enter address"
                    class="form-control"
                />
            </div>
            <div class="col-12 mb-2">
                <button type="submit" class="btn btn-primary btn-block">
                    Submit
                </button>
            </div>
        </form>
        <p>Latitude:</p>
        <p>{{ lat }}</p>
        <p>Longitude:</p>
        <p>{{ lng }}</p>
        <div class="map">
            <gmap-map
                :center="myCoordinates"
                :zoom="10"
                style="width: 100%; height: 420px"
                ref="mapRef"
                @dragend="handleDrag"
            >
                <gmap-info-window
                    :options="infoWindowOptions"
                    :position="myCoordinates"
                    :opened="myInfoWindowOpened"
                    @closeclick="handleCloseClick"
                >
                    <div class="info-window-me">
                        <h3>My location</h3>
                        <p>Latitude: {{ myCoordinates.lat.toFixed(4) }}</p>
                        <p>Longitude: {{ myCoordinates.lng.toFixed(4) }}</p>
                    </div>
                </gmap-info-window>
                <gmap-marker
                    :position="myCoordinates"
                    :clickable="true"
                    :draggable="false"
                    :icon="blueMark"
                    @click="myInfoWindowOpened = true"
                >
                </gmap-marker>
                <gmap-info-window
                    :options="infoWindowOptions"
                    :position="infoWindowPosition"
                    :opened="infoWindowOpened"
                    @closeclick="handleCloseClick"
                >
                    <div class="info-window">
                        <h3>{{ activeWorkshop.name }}</h3>
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

        <button @click="logout">Logout</button>
    </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
    name: "dashboard",

    data() {
        return {
            form: {
                address: "",
            },
            lat: "",
            lng: "",
            user: this.$store.state.auth.user,
            profileData: {
                name: "",
                email: "",
            },
            map: null,
            blueMark:
                "http://maps.google.com/mapfiles/kml/paddle/blu-circle.png",
            myCoordinates: {
                lat: 0,
                lng: 0,
            },
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
            workshops: [],
        };
    },

    computed: {
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

        async logout() {
            await axios.post("/logout").then(({ data }) => {
                this.signOut();
                this.$router.push({ name: "login" });
            });
        },

        handleDrag() {
            //get center and zoom level, store in local storage
            //more and more
        },

        async getLatLng() {
            await axios
                .get("/api/latLng/" + this.form.address)
                .then((response) => {
                    console.log(response.data);
                    this.lat = response.data.data.lat;
                    this.lng = response.data.data.lng;
                })
                .catch((error) => {
                    alert(error);
                });
        },

        async updateProfile() {
            await axios
                .put("/user/profile-information", this.profileData)
                .then((response) => {
                    console.log(response.data);
                })
                .catch((error) => {
                    alert(error);
                });
        },

        getWorkshopPosition(workshop) {
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
        this.$refs.mapRef.$mapPromise.then((map) => (this.map = map));
    },

    created() {
        //get user's coordinates
        this.$getLocation({})
            .then((coordinates) => {
                this.myCoordinates = coordinates;
            })
            .catch((error) => alert(error));

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

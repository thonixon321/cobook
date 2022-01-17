<template>
    <div>
        <h2>Hello {{ user.name }}!</h2>
        <div class="map">
            <p>Your coordinates:</p>
            <p>
                {{ myCoordinates.lat.toFixed(4) }} Latitude;
                {{ myCoordinates.lng.toFixed(4) }} Longitude.
            </p>
            <gmap-map
                :center="myCoordinates"
                :zoom="7"
                style="width: 100%; height: 420px"
                ref="mapRef"
                @dragend="handleDrag"
            >
                <gmap-marker
                    :position="myCoordinates"
                    :clickable="true"
                    :draggable="false"
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
            user: this.$store.state.auth.user,
            map: null,
            myCoordinates: {
                lat: 0,
                lng: 0,
            },
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
                console.log(this.myCoordinates);
            })
            .catch((error) => alert(error));
    },
};
</script>

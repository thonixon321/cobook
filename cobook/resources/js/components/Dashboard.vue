<template>
    <div>
        <h2>Hello {{ user.name }}!</h2>
        <div class="map">
            <GmapMap
                :center="{ lat: 10, lng: 10 }"
                :zoom="7"
                style="width: 100%; height: 320px"
            >
            </GmapMap>
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
        };
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
    },
};
</script>

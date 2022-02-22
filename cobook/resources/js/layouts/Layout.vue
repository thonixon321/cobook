<template>
    <div class="container">
        <div class="nav_comp">
            <div class="profile_container">
                <div class="thumbnail">
                    <img v-if="thumbnail" :src="thumbnail" />
                    <div v-else>
                        {{ user.name.charAt(0).toUpperCase() }}
                    </div>
                </div>
                <span class="name">{{ user.name }}</span>
            </div>
            <div class="navLink_container">
                <div
                    class="navLink_container--item dashboard"
                    :class="{ active: dashboardActive }"
                >
                    <router-link :to="{ name: 'dashboard' }">
                        <dashboard-icon />
                        <span>Dashboard</span>
                    </router-link>
                </div>
                <div
                    class="navLink_container--item workshops"
                    :class="{ active: workshopsActive }"
                >
                    <router-link :to="{ name: 'workshops' }">
                        <workshops-icon />
                        <span>Workshops</span>
                    </router-link>
                </div>
                <div
                    class="navLink_container--item host"
                    :class="{ active: hostActive }"
                >
                    <router-link :to="{ name: 'host' }">
                        <host-icon />
                        <span>Hosting</span>
                    </router-link>
                </div>
            </div>
            <button @click="logout" class="signOut">Sign Out</button>
        </div>
        <div class="main_content">
            <slot></slot>
        </div>
    </div>
</template>

<script>
import DashboardIcon from "../components/svgs/DashboardIcon.vue";
import HostIcon from "../components/svgs/HostIcon.vue";
import WorkshopsIcon from "../components/svgs/WorkshopsIcon.vue";
import { mapState, mapGetters, mapActions } from "vuex";
export default {
    name: "Layout",

    data() {
        return {};
    },

    computed: {
        ...mapState({
            dashboardActive: "dashboardActive",
            workshopsActive: "workshopsActive",
            hostActive: "hostActive",
        }),

        ...mapGetters({
            user: "auth/user",
            thumbnail: "auth/getThumbnail",
        }),
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

    components: {
        DashboardIcon,
        HostIcon,
        WorkshopsIcon,
    },
};
</script>

<style scoped>
a {
    text-decoration: none;
    color: white;
}

a:hover {
    color: white;
}

div.container {
    display: flex;
    position: relative;
}

div.profile_container {
    display: flex;
    align-items: center;
    margin: 1em;
    color: white;
}

div.nav_comp {
    width: 12em;
    height: 100%;
    background: #945d45;
    position: fixed;
    top: 0;
    left: 0;
}
div.nav_comp button.signOut {
    background: transparent;
    border: none;
    color: white;
    margin-left: 4em;
    position: absolute;
    bottom: 4em;
    padding: 1em;
    cursor: pointer;
}
div.main_content {
    width: 100%;
    margin-left: 13em;
}
.navLink_container {
    margin-left: 1em;
    margin-top: 2em;
}
.navLink_container--item {
    opacity: 0.5;
}
.navLink_container--item a {
    display: flex;
    align-items: center;
}
.navLink_container--item svg {
    width: 25px;
}
.navLink_container--item.active {
    opacity: 1;
}
.navLink_container--item span {
    margin-left: 0.53em;
}
.profile_container .name {
    margin-left: 0.53em;
}
.profile_container .thumbnail {
    width: 2em;
    height: 2em;
    border-radius: 50%;
    background: #3f2519;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>

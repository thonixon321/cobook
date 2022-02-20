<template>
    <div>
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
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                address: "",
            },
            lat: "",
            lng: "",
        };
    },
    methods: {
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
    },
};
</script>

<style></style>

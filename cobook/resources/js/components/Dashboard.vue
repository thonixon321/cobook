<template>
    <div>
        
         Hello {{user.name}}!
    
        <button @click="logout">Logout</button>
    </div>
</template>

<script>
import {mapActions} from 'vuex'
export default {
    name:"dashboard",
    data(){
        return {
            user:this.$store.state.auth.user
        }
    },
    methods:{
        ...mapActions({
            signOut:"auth/logout"
        }),
        async logout(){
            await axios.post('/logout').then(({data})=>{
                this.signOut()
                this.$router.push({name:"login"})
            })
        }
    }
}
</script>
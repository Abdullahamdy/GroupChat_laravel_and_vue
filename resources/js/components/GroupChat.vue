<template>
    <div>
        <section class="gradient-custom">
            <div class="container py-5">


                <div class="row">

                    <div class="col-md-6 col-lg-5 col-xl-5 mb-4 mb-md-0">

                        <h5 class="font-weight-bold mb-3 text-center text-white">Groups</h5>

                        <div class="card mask-custom">
                            <div class="card-body">

                                <ul class="list-unstyled mb-0">
                                    <li class="p-2 border-bottom" v-for="group in groups" :key="group.id"
                                        @click="activeGroupfun(group.id)"
                                        style="border-bottom: 1px solid rgba(255,255,255,.3) !important;">
                                        <a href="#!" class="d-flex justify-content-between link-light">
                                            <div class="d-flex flex-row">
                                                <img src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/avatar-8.webp"
                                                    alt="avatar"
                                                    class="rounded-circle d-flex align-self-center me-3 shadow-1-strong"
                                                    width="60">
                                                <div class="pt-1">
                                                    <p class="fw-bold mb-0">{{ group.name }}</p>
                                                    <p class="small text-white" v-if="group.last_message">{{ group.last_message.body }}</p>
                                                </div>
                                            </div>
                                            <div class="pt-1">
                                                <p class="small text-white mb-1">Just now</p>
                                                <span class="badge bg-danger float-end">1</span>
                                            </div>
                                        </a>
                                    </li>

                                </ul>

                            </div>


                        </div>
                        <div class="col-md-6 col-lg-5 mt-4" style="flex-direction: column;">
                            <button @click="toggleDropdown" class="btn btn-primary rounded-pill">Add Group</button>
                        </div>
                        <div v-if="showDropdown">
                            <div>
                                <input type="text" v-model="AddGroup.GroupName" class="mt-2" placeholder="Group Name"
                                    style="font-weight:bold; border-radius: 10px;height: 50px;">
                                <h2 style="color: #007bff;">Members</h2>
                                <div @click="AddMember(1)" class="user-details" style="display:inline-block">
                                    <div v-if="AddGroup.Members.includes(1)" :style="(AddGroup.Members.includes(1)) ? { 'font-weight': 'bolder' } : ''">
                                        <h2 style="margin-top: 10px;margin-left: 10px;">
                                            <span>&#x2611;</span>
                                        </h2>
                                    </div>
                                    <div style="position: relative;">
                                    <img src="/images/Abdullah.jpg" width="50px;hight:50px" alt="User Photo" />
                                      </div>
                                    <p class="" style="color: #0056b3;font-weight:bolder">Abdullah</p>
                                </div>
                                <div @click="AddMember(2)" class="user-details" style="display:inline-block">
                                    <div v-if="AddGroup.Members.includes(2)" :style="(AddGroup.Members.includes(1)) ? { 'font-weight': 'bolder' } : ''">
                                        <h2 style="margin-top: 10px;margin-left: 10px;">
                                            <span>&#x2611;</span>
                                        </h2>
                                    </div>
                                    <img src="/images/Abdullah.jpg" width="50px;hight:50px" alt="User Photo" />
                                    <p class="" style="color: #0056b3; font-weight:bolder">Abdullah</p>
                                </div>
                                <div @click="AddMember(3)" class="user-details" style="display:inline-block">

                                    <div v-if="AddGroup.Members.includes(3)" :style="(AddGroup.Members.includes(1)) ? { 'font-weight': 'bolder' } : ''">
                                        <h2 style="margin-top: 10px;margin-left: 10px;">
                                            <span>&#x2611;</span>

                                        </h2>
                                    </div>
                                    <img src="/images/Abdullah.jpg" width="50px;hight:50px" alt="User Photo" />
                                    <p style="color: #0056b3; font-weight:bolder">Abdullah</p>
                                </div>
                            </div>
                            <div class="user-details" style="display:inline-block">
                                <button @click="AddNewGroup" class="btn btn-primary rounded-pill">Add</button>
                            </div>
                        </div>

                    </div>


                    <div class="col-md-6 col-lg-7 col-xl-7" v-if="activeGroup != null">

                        <ul class="list-unstyled text-white">
                            <!-- sender -->
                            <li class="d-flex justify-content-between mb-4" v-for="(message, index) in groupMessages"
                                :key="index">
                                <img v-if="user.id != message.user_id"
                                    src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/avatar-6.webp" alt="avatar"
                                    class="rounded-circle d-flex align-self-start me-3 shadow-1-strong" width="60">
                                <div class="card mask-custom">
                                    <div class="card-header d-flex justify-content-between p-3"
                                        style="border-bottom: 1px solid rgba(255,255,255,.3);">
                                        <p class="fw-bold mb-0">{{ message.user.name }}</p>
                                        <p class="text-light small mb-0"><i class="far fa-clock"></i> 12 mins ago</p>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0">
                                            {{ message.body }}
                                        </p>
                                    </div>
                                </div>
                            </li>


                            <li class="mb-3">
                                <div class="form-outline form-white">
                                    <textarea class="form-control" id="textAreaExample3" v-model="message"
                                        rows="4"></textarea>
                                    <label class="form-label" for="textAreaExample3">Message</label>
                                </div>
                            </li>
                            <button @click="sendMessage" type="button"
                                class="btn btn-light btn-lg btn-rounded float-end">Send</button>
                        </ul>

                    </div>
                </div>


            </div>
        </section>
    </div>
</template>
<script>
export default {
    props: ['user'],
    data() {
        return {
            groups: '',
            activeGroup: null,
            groupMessages: [],
            message: '',
            showDropdown: false,
            AddGroup: {
                GroupName: '',
                Members: [],
            }

        }
    },
    methods: {
        fetchGroups() {
            axios.get('/get-groups').then(response => {
                this.groups = response.data.groups;

            });

        },
        fetchMessage() {
            axios.get(`/conversation/${this.activeGroup}`).then(response => {
                this.groupMessages = response.data.groupMessages;
            });

        },
        activeGroupfun(groupId) {
            Echo.leave(`PrivateGroupChat.${this.activeGroup}`);
            this.activeGroup = groupId
        },

        sendMessage() {

            const conversationId = this.activeGroup;
            if (!this.message) {
                return alert('please Enter Message');
            }
            axios.post(`/create-message`, { 'body': this.message, 'conversation_id': conversationId }).then(response => {
                this.message = null;
                this.groupMessages.push(response.data)

                this.message = null;

            });
        },


        // functionalty Groups

        toggleDropdown() {
            this.showDropdown = !this.showDropdown;
            console.log(this.showDropdown)
        },
        AddMember(memberId) {

            let index = this.AddGroup.Members.indexOf(memberId);
            if (index !== -1) {
                this.AddGroup.Members.splice(index, 1);
            } else {
                this.AddGroup.Members.push(memberId)
            }
            console.log(this.AddGroup.Members)

        },
        AddNewGroup(){
            axios.post(`/add-new-group`, {  'group': this.AddGroup }).then(response => {
                this.message = null;
                this.groupMessages.push(response.data)
                this.message = null;

            });
            console.log(this.AddGroup)
        }


    },

    watch: {
        activeGroup(val) {
            console.log(val)
            this.fetchMessage();
        }

    },



    created() {
        this.fetchGroups();
        this.$watch('activeGroup', (newValue) => {
            if (newValue) {
                Echo.leave(`PrivateGroupChat.${this.activeGroup}`);
                Echo.private('PrivateGroupChat.' + this.activeGroup)
                    .listen('PrivateGroupSent', (e) => {
                        this.groupMessages.push(e.message);
                    })
            }
        });
    }
}



















</script>
<style scoped>
.gradient-custom {
    /* fallback for old browsers */
    background: #fccb90;

    /* Chrome 10-25, Safari 5.1-6 */
    background: -webkit-linear-gradient(to bottom right, rgba(252, 203, 144, 1), rgba(213, 126, 235, 1));

    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    background: linear-gradient(to bottom right, rgba(252, 203, 144, 1), rgba(213, 126, 235, 1))
}

.mask-custom {
    background: rgba(24, 24, 16, .2);
    border-radius: 2em;
    backdrop-filter: blur(15px);
    border: 2px solid rgba(255, 255, 255, 0.05);
    background-clip: padding-box;
    box-shadow: 10px 10px 10px rgba(46, 54, 68, 0.03);
}

.user-details {
    display: flex;
    align-items: center;
}

.user-details img {
    margin-right: 10px;
    width: 50px;
    height: 50px;
}

.active {
    color: green;
}

.inactive {
    color: red;
}
</style>

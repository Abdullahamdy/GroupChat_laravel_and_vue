<template>
    <main class="content">
        <div class="container p-0">

            <h1 class="h3 mb-3">Friends</h1>
            <div style="text-align: center;position: relative;">
                <div style="background-color:black;color: #fff;width: 150px;position: absolute;bottom: 27px;right: 417px;">
                    Hii {{ user.name }} !
                </div>
            </div>
            <div class="card">
                <div class="row g-0">
                    <div class="col-12 col-lg-5 col-xl-3 border-right">

                        <div class="px-4 d-none d-md-block">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <input type="text" class="form-control my-3" placeholder="Search...">
                                </div>
                            </div>
                        </div>


                        <a class="list-group-item list-group-item-action border-0 reciver_id" v-for=" friend in friends"
                            :key="friend.id"
                            :style="{ backgroundColor: activeFriend == friend.id ? 'black' : '', color: activeFriend ? '#fff' : '' }">
                            <div class="badge bg-success float-right">0</div>
                            <div class="d-flex align-items-start">
                                <img :src="'images/' + friend.image" class="rounded-circle mr-1" alt="Vanessa Tucker"
                                    width="40" height="40">
                                <div class="flex-grow-1 ml-3 btn" @click="activeFriend = friend.id"
                                    :style="{ color: onlineFriends.find(onlineFriend => onlineFriend.id == friend.id) ? 'green' : 'red' }">
                                    {{ friend.name }}
                                    <div class="small"><span class="fas fa-circle chat-online"></span> {{
                                        onlineFriends.find(onlineFriend => onlineFriend.id == friend.id) ? 'Online' : '' }}
                                    </div>
                                </div>
                            </div>
                        </a>

                        <hr class="d-block d-lg-none mt-1 mb-0">
                    </div>
                    <div class="col-12 col-lg-7 col-xl-9">

                        <div class="py-2 px-4 border-bottom d-none d-lg-block">
                            <div class="d-flex align-items-center py-1">
                                <div class="block-div" v-if="activeFriend">
                                    <button class="btn btn-danger" @click="BlockOrNot()">{{ !isBlockbutton ? 'Block' :
                                        'UnBlock' }}</button>
                                </div>
                                <div class="position-relative">
                                    <img :src="'images/' + selectedUser.image" v-if="selectedUser.image"
                                        class="rounded-circle mr-1" :alt="selectedUser.name" width="40" height="40">

                                </div>
                                <div class="flex-grow-1 pl-3">
                                    <strong v-if="typingFriend.name">{{ activeFriend.name }}</strong>
                                    <div class="text-muted small"><em v-if="typingFriend.name">{{ typingFriend.name }} is
                                            Typing</em></div>
                                </div>

                            </div>
                        </div>



                        <div class="position-relative">
                            <div class="chat-messages p-4">

                                <div v-for="(m, i) in allmessages" :key="i"
                                    :class="(user.id == m.user.id) ? 'chat-message-right mt-2' : 'chat-message-left mt-2'">
                                    <div>
                                        <div class="text-muted small text-nowrap mt-2">{{ m.created_at }}</div>
                                    </div>

                                    <div class="flex-shrinv-1 bg-light rounded py-2 px-3 mr-3">
                                        <div class="font-weight-bold mb-1">{{ m.user.name }}</div>
                                        <div v-if="m.body != 'null'">
                                            {{ m.body }}
                                        </div>


                                        <div class="image-container rounded" v-if="m.attachment"
                                            style="width:200px;height: 60px; overflow: hidden;">
                                            <img :src="m.attachment" alt="" style="width: 100%;">
                                        </div>
                                    </div>
                                </div>

                                <div v-if="isBlockinput" class="flex-grow-0 py-3 px-4 border-top hidden-text text-center">
                                    <p>This is Person Not Available Now... !</p>
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class="flex-grow-0 py-3 px-4 border-top">
                            <div class="input-group" v-if="activeFriend">
                                <input type="file" id="attachment-input" ref="attachmentInput" style="display: none;"
                                    @input="handleFileSelection">
                                <input type="text" id='chatMessage' :disabled="!activeFriend" class="form-control"
                                    v-model="message"
                                    :placeholder="(activeFriend ? 'Type your message' : 'Please Select Friend')"
                                    @keydown="onTyping">
                                <i @click="openFilePicker" class="attachment-icon fas fa-paperclip"></i>
                                <button :disabled="isButtonDisabled" class="btn btn-primary" id="sendMessage"
                                    @click="sendMessage()">Send</button>

                            </div>

                        </div>
                        <div lass="image-container" v-if="url" style="width:100px;height: 100px;">
                            <img :src="url" alt="" style="width: 100%;">
                            <span class="remove-icon" @click="url = false">&#10006;</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
export default {

    data() {
        return {
            message: null,
            user: [],
            allmessages: [],
            users: [],
            typingClock: null,
            onlineFriends: [],
            activeFriend: null,
            typingFriend: {},
            selectedUser: {},
            isBlockbutton: null,
            isBlockinput: null,
            userBlocked: {},
            url: false,
            attachment: '',


        }
    },
    computed: {
        friends() {
            return this.users.filter((user) => {
                return user.id !== this.user.id;
            })
        },
        isButtonDisabled() {
            return !(this.message || this.url);
        }



    },

    mounted() {
        if (this.$route.params.id != undefined || this.$route.params.id != null) {



        }
    },
    watch: {
        activeFriend(val) {
            Echo.leave(`PrivateChat.${this.activeFriend}`);
            this.fetchMessage();
        }

    },



    methods: {
        onTyping() {
            Echo.private('PrivateChat.' + this.activeFriend)
                .whisper('typing', {
                    user: this.user,

                })



        },
        openFilePicker() {
            this.$refs.attachmentInput.click();
        },
        handleFileSelection(e) {
            const file = e.target.files[0];
            this.attachment = file;
            this.url = URL.createObjectURL(file);
            URL.revokeObjectURL(file)
        },
        BlockOrNot() {
            axios.post(`/block-user/${this.activeFriend}`).then(res => {
                // this.isBlockinput = res.data.isBlock;
                this.isBlockbutton = res.data.isBlock;
                this.userBlocked = res.data.blockFriend;
                // this.isBlockinput = true;

            }).catch((err) => {
            })
        },


        sendMessage() {

            const formData = new FormData();
            formData.append('image', this.attachment);
            formData.append('message', this.message),
                axios.post(`/private-message/${this.activeFriend}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },


                }).then(response => {
                    console.log(response);
                    this.message = null;
                    if (response.data != '') {
                        this.isBlockinput = false;
                        this.allmessages.push(response.data.message)
                    } else {

                        this.isBlockinput = true;
                    }
                    this.url = false;
                    this.attachment = false;
                    setTimeout(this.scrollToEnd, 100);

                });
        },
        fetchMessage() {
            axios.get(`/messages/${this.activeFriend}`).then(response => {

                const foundUserIndex = this.friends.findIndex(user => user.id === this.activeFriend);
                this.selectedUser = this.friends[foundUserIndex];
                this.allmessages = response.data.messages;
                this.isBlockinput = response.data.isBlock.isBlock;
                this.isBlockbutton = response.data.isBlock.isBlock;
                this.userBlocked = response.data.isBlock.blockFriend

            });

        },
        fetchUsers() {
            axios.get('/get-users').then(response => {
                this.users = response.data.users;
                if (this.$route.params.id != undefined || this.$route.params.id != null) {
                    const foundUserIndex = this.friends.findIndex(user => user.id === this.$route.params.id);
                    this.selectedUser = this.friends[foundUserIndex];
                    this.activeFriend = this.friends[foundUserIndex].id;


                }
            });

        },
        scrollToEnd() {
            window.scrollTo(0, 99999)
        }
    },

    created() {
        this.user = window.authUser;
        this.fetchUsers();
        Echo.join(`plchat`)
            .here((users) => {
                this.onlineFriends = users;
            })
            .joining((user) => {
                this.onlineFriends.push(user)
            })
            .leaving((user) => {
                this.onlineFriends.splice(this.onlineFriends.indexOf(user), 1)
            })
            .error((error) => {
            });
        Echo.leave(`PrivateChat.${this.user.id}`);
        Echo.private('PrivateChat.' + this.user.id)

            .listen('PrivateMessageSent', (e) => {
                if (e.message.receiver == this.user_id) {
                    this.allmessages.push(e.message);
                }
                setTimeout(this.scrollToEnd, 100);
            })
            .listenForWhisper('typing', (e) => {
                if (e.user.id == this.activeFriend) {
                    this.typingFriend = e.user;
                    if (this.typingClock) clearTimeout();

                    this.typingClock = setTimeout(() => {
                        this.typingFriend = {};

                    }, 9000)
                }

            })
        Echo.private('PrivatesendBlock.' + this.user.id)
            .listen('BlockFriend', (e) => {
                if (e.userblocked == 'UnBlocked') {
                    this.isBlockinput = false;
                } else {
                    this.isBlockinput = true;
                }

            })


    },





}
</script>
<style scoped>
.block-div {
    position: absolute;
    top: 16px;
    right: 50px;
}

.hidden-text {
    text-shadow: 0 0 2px rgba(0, 0, 0, 1.5);
    color: transparent;
}

.attachment-icon {
    margin: 10px;
    font-size: 20px;
}
</style>

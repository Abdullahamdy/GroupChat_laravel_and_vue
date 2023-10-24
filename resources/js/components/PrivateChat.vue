<template>
    <main class="content">
        <div class="container p-0">

            <h1 class="h3 mb-3">Messages</h1>

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
                            :key="friend.id">
                            <div class="badge bg-success float-right">0</div>
                            <div class="d-flex align-items-start">
                                <!-- <img :src="'Images/' + friend.image" class="rounded-circle mr-1" alt="Vanessa Tucker" -->
                                    <!-- width="40" height="40"> -->
                                <div class="flex-grow-1 ml-3 btn" @click="activeFriend = friend.id" :style="{color: onlineFriends.find(onlineFriend=>onlineFriend.id == friend.id) ? 'green' :'red' }">
                                    {{ friend.name }}
                                    <div class="small"><span class="fas fa-circle chat-online" ></span> {{ onlineFriends.find(onlineFriend=>onlineFriend.id == friend.id) ? 'Online' : '' }}</div>
                                </div>
                            </div>
                        </a>

                        <hr class="d-block d-lg-none mt-1 mb-0">
                    </div>
                    <div class="col-12 col-lg-7 col-xl-9">

                        <div class="py-2 px-4 border-bottom d-none d-lg-block">
                            <div class="d-flex align-items-center py-1">
                                <div class="position-relative">
                                    <!-- <img :src="'Images/' + activeFriend.image" v-if="activeFriend"
                                        class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40"> -->
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
                                        <!-- <img src="" class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40"> -->
                                        <div class="text-muted small text-nowrap mt-2">{{ m.created_at }}</div>
                                    </div>

                                    <div class="flex-shrinv-1 bg-light rounded py-2 px-3 mr-3">
                                        <div class="font-weight-bold mb-1">{{ m.user.name }}</div>
                                        {{ m.message }}
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class="flex-grow-0 py-3 px-4 border-top">
                            <div class="input-group">
                                <input type="text" id='chatMessage' :disabled="!activeFriend" class="form-control"
                                    v-model="message"
                                    :placeholder="(activeFriend ? 'Type your message' : 'Please Select Friend')"
                                    @keydown="onTyping">
                                <button class="btn btn-primary" id="sendMessage" @click="sendMessage()">Send</button>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            message: null,
            allmessages: [],
            users: [],
            typingClock: null,
            onlineFriends: [],
            activeFriend: null,
            typingFriend: {},


        }
    },
    computed: {
        friends() {
            return this.users.filter((user) => {
                return user.id !== this.user.id;
            })
        }

    },
    watch: {
        activeFriend(val) {
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
        sendMessage() {
            if (!this.message) {
                return alert('please Enter Message');
            }
            axios.post(`/private-message/${this.activeFriend}`, { 'message': this.message }).then(response => {
                this.message = null;
                this.allmessages.push(response.data.message)
                setTimeout(this.scrollToEnd, 100);

            });
        },
        fetchMessage() {
            axios.get(`/messages/${this.activeFriend}`).then(response => {
                this.allmessages = response.data.messages;

            });

        },
        fetchUsers() {
            axios.get('/get-users').then(response => {
                this.users = response.data.users;
                this.activeFriend = this.friends[0].id;
            });

        },
        scrollToEnd() {
            window.scrollTo(0, 99999)
        }
    },

    created() {
        this.fetchUsers();
        Echo.join(`plchat`)
            .here((users) => {
                this.onlineFriends = users;
            })
            .joining((user) => {
                this.onlineFriends.push(user)
                console.log(user.name);
            })
            .leaving((user) => {
                this.onlineFriends.splice(this.onlineFriends.indexOf(user),1)
                console.log(user.name);
            })
            .error((error) => {
                console.error(error);
            });
        Echo.private('PrivateChat.' + this.user.id)

            .listen('PrivateMessageSent', (e) => {
                this.activeFriend = e.message.user_id;
                this.allmessages.push(e.message);
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
    },





}
</script>

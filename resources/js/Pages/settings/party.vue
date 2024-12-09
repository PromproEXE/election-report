<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import _ from 'lodash'
</script>
<script>
export default {
    data() {
        return {
            partyList: [],
            selectedParty: {
                party_number: null,
                party_name: null
            },
            addPartyData: {
                id: null,
                party_number: null,
                party_name: null
            },
            rootUrl: route('setting-party')
        }
    },
    methods: {
        selectData(data) {
            this.selectedParty = _.cloneDeep(data)
        },
        async getPartyList() {
            try {
                let res = await axios('/api/party')
                this.partyList = res.data
            }
            catch (err) {
                alert(err)
            }
        },
        async deleteParty(id) {
            try {
                let res = await axios.delete('/api/party/' + id + '/delete')
                if (res.statusText == 'OK') {
                    alert('DELETE')
                    this.closeModal()
                }
                else {
                    alert('ERROR')
                }
            }
            catch (err) {
                alert(err)
            }
            this.getPartyList()
        },
        async addParty() {
            try {
                //CREATE
                let res = await axios.post('/api/party/create', this.addPartyData)
                if (res.statusText == 'Created') {
                    alert('ADD PARTY')
                    this.closeModal()
                }
                else {
                    alert('ERR')
                }
            }
            catch (err) {
                alert(err)
            }
            this.getPartyList()
        },
        async updateParty() {
            try {
                let res = await axios.put('/api/party/' + this.selectedParty.id + '/update', this.selectedParty)
                if (res.statusText == 'OK') {
                    alert('UPDATE')
                    this.closeModal()
                }
            }
            catch (err) {
                alert(err)
            }
            this.getPartyList()
        },
        closeModal() {
            window.location.href = this.rootUrl + '#'
        }
    },
    async mounted() {
        await this.getPartyList()
    }
}
</script>
<template>
    <AppLayout title="ตั้งค่าพรรค">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ตั้งค่าพรรค
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <p class="text-6xl font-bold mb-5">ตั้งค่าพรรค</p>

                <div class="flex justify-end mb-3">
                    <a href="#add-modal" role="button" class="btn btn-success">เพิ่มพรรค</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <!-- head -->
                        <thead>
                            <tr>
                                <th>หมายเลขพรรค</th>
                                <th>ชื่อพรรค</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="party in partyList">
                                <td>{{ party.party_number }}</td>
                                <td>{{ party.party_name }}</td>
                                <td>
                                    <a href="#edit-modal" role="button" class="btn btn-warning mr-3"
                                        @click="selectData(party)">แก้ไข</a>
                                    <a href="#delete-modal" role="button" class="btn btn-error"
                                        @click="selectData(party)">ลบ</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal" id="delete-modal">
            <div class="modal-box text-center relative">
                <div class="flex justify-end">
                    <a role="button" href="#" class="btn btn-circle btn-ghost">X</a>
                </div>
                <p class="text-xl mb-9">คุณต้องการลบ <span class="font-bold">{{ selectedParty.party_name }}</span>
                    หรือไม่</p>
                <div class="grid grid-cols-2">
                    <a href="#" role="button" class="btn base-100 mr-2 text-lg">ยกเลิก</a>
                    <button class="btn btn-error text-lg" @click="deleteParty(selectedParty.id)">ลบ</button>
                </div>
            </div>
        </div>

        <div class="modal" tabindex="-1" id="edit-modal">
            <div class="modal-box relative">
                <div class="flex justify-between items-center">
                    <p class="font-bold text-2xl">แก้ไขข้อมูลพรรค</p>
                    <a href="#" role="button" class="btn btn-circle btn-ghost top-1">✕</a>
                </div>
                <label for="edit-add-name" class="label">
                    <h6>หมายเลขพรรค</h6>
                </label>
                <input type="number" min="1" id="edit-add-name" class="input input-bordered w-full"
                    v-model="selectedParty.party_number" @keyup.enter="updateParty(selectedParty)">
                <label for="edit-add-password" class="label">
                    <h6>ชื่อพรรค</h6>
                </label>
                <input type="text" id="edit-add-password" class="input input-bordered w-full"
                    v-model="selectedParty.party_name" @keyup.enter="updateParty(selectedParty)">
                <div class="modal-action">
                    <a class="btn btn-base-100" href="#">ยกเลิก</a>
                    <a class="btn btn-success" @click="updateParty(selectedParty)">บันทึก</a>
                </div>
            </div>
        </div>

        <div class="modal" tabindex="-1" id="add-modal">
            <div class="modal-box relative">
                <div class="flex justify-between items-center">
                    <p class="font-bold text-2xl">เพิ่มพรรค</p>
                    <a href="#" role="button" class="btn btn-circle btn-ghost top-1">✕</a>
                </div>
                <label for="edit-add-name" class="label">
                    <h6>หมายเลขพรรค</h6>
                </label>
                <input type="number" min="1" id="edit-add-name" class="input input-bordered w-full"
                    v-model="addPartyData.party_number">
                <label for="edit-add-password" class="label">
                    <h6>ชื่อพรรค</h6>
                </label>
                <input type="text" id="edit-add-password" class="input input-bordered w-full"
                    v-model="addPartyData.party_name">
                <div class="modal-action">
                    <a class="btn btn-base-100" href="#">ยกเลิก</a>
                    <a class="btn btn-success" type="button" @click="addParty()">บันทึก</a>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

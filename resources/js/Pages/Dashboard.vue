<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import axios from 'axios';
</script>

<script>
export default {
    data() {
        return {
            rawData: [],
            partyList: [],
            score: {
                elector: 100,
                voted: 50,
                voted_per_elector: 0,
                voted_data: {}
            },
            selectedClass: 'ทั้งหมด'
        }
    },
    methods: {
        async getScore() {
            try {
                let res = await axios('/api/score/')
                this.rawData = res.data

                await this.processData()
            }
            catch (err) {
                console.log(err)
            }
        },
        async getScoreByClass() {
            try {
                let res = await axios('/api/score/' + this.selectedClass)
                this.rawData = res.data

                this.processData()
            }
            catch (err) {
                console.log(err)
            }
        },
        async getParty() {
            try {
                let res = await axios('/api/party/')
                this.partyList = res.data
                this.partyList.push({
                    party_number: '0',
                    party_name: 'ไม่ประสงค์ลงคะแนน'
                })
            }
            catch (err) {
                console.log(err)
            }
        },
        processData() {
            this.score.elector = this.rawData.length
            this.score.voted = this.rawData.filter((datas) => datas.vote != null).length
            this.score.voted_per_elector = ((this.score.voted / this.score.elector) * 100).toFixed(2)

            //GET VOTE AMOUNT
            for (let party of this.partyList) {
                this.score.voted_data[party.party_number] = {
                    amount: this.rawData.reduce((prev, data) => {
                        if (data.vote == party.party_number) {
                            return prev + 1
                        }
                        return prev
                    }, 0)
                }
            }
        }
    },
    async mounted() {
        await this.getParty()
        await this.getScore()
    }
}
</script>

<template>
    <AppLayout title="สรุปผลคะแนน">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                สรุปผลคะแนน
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <p class="text-6xl font-bold mb-5">สรุปผลคะแนน</p>
                <div class="grid grid-cols-3 gap-4 mb-5">
                    <button class="btn btn-success text-2xl col-span-3"
                        @click="selectedClass = 'ทั้งหมด'; getScore()">ทั้งหมด</button>
                    <button class="btn btn-secondary text-2xl"
                        @click="selectedClass = '1'; getScoreByClass()">ม.1</button>
                    <button class="btn btn-secondary text-2xl"
                        @click="selectedClass = '2'; getScoreByClass()">ม.2</button>
                    <button class="btn btn-secondary text-2xl"
                        @click="selectedClass = '3'; getScoreByClass()">ม.3</button>
                    <button class="btn btn-secondary text-2xl"
                        @click="selectedClass = '4'; getScoreByClass()">ม.4</button>
                    <button class="btn btn-secondary text-2xl"
                        @click="selectedClass = '5'; getScoreByClass()">ม.5</button>
                    <button class="btn btn-secondary text-2xl"
                        @click="selectedClass = '6'; getScoreByClass()">ม.6</button>
                </div>

                <div class="grid grid-cols-4 my-7 bg-white p-5 rounded-2xl drop-shadow-lg">
                    <div>
                        <p class="text-md mb-5 text-gray-500">ระดับชั้น</p>
                        <p class="text-6xl font-bold text-primary">{{ selectedClass != 'ทั้งหมด' ? 'ม.' + selectedClass
                                : selectedClass
                        }}</p>
                    </div>
                    <div>
                        <p class="text-md mb-5 text-gray-500">จำนวนผู้มีสิทธิ์เลือกตั้ง</p>
                        <p class="text-6xl font-bold text-info">{{ score.elector }}</p>
                    </div>
                    <div>
                        <p class="text-md mb-5 text-gray-500">จำนวนผู้มาลงคะแนน</p>
                        <p class="text-6xl font-bold text-warning">{{ score.voted }}</p>
                    </div>
                    <div>
                        <p class="text-md mb-5 text-gray-500">คิดเป็นร้อยละ</p>
                        <p class="text-6xl font-bold"
                            :class="{ 'text-success': score.voted_per_elector > 70, 'text-warning': score.voted_per_elector > 50, 'text-error': score.voted_per_elector > 0 }">
                            {{
                                    score.voted_per_elector
                            }}%
                        </p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <!-- head -->
                        <thead>
                            <tr>
                                <th>อันดับที่</th>
                                <th>เบอร์</th>
                                <th>ชื่อพรรค</th>
                                <th>คะแนนที่ได้รับ</th>
                                <th>คิดเป็นร้อยละ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- row 1 -->
                            <tr v-for="party in partyList">
                                <td>Blue</td>
                                <th>{{ party.party_number }}</th>
                                <td>{{ party.party_name }}</td>
                                <td>{{ score.voted_data[party.party_number].amount || 0 }}</td>
                                <td>{{ ((score.voted_data[party.party_number].amount / score.voted) * 100).toFixed(2) ||
                                        0
                                }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>รวม</td>
                                <td></td>
                                <td></td>
                                <td>{{ score.voted }}</td>
                                <td>100</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

var vue = new Vue({
    el: '#building',
    data:{
        message: 'Hello world',
        buildings: [
            { id: 1001, name: 'Building1'},
            { id: 1002, name: 'Building2'},
            { id: 1003, name: 'Building3'},
            { id: 1004, name: 'Building4'},
            { id: 1005, name: 'Building5'},
            { id: 1006, name: 'Building6'},
            { id: 1007, name: 'Building7'}
        ],
        rooms: [
            { floor_cnt: 1, room_num: 110, tubo_cnt: 18.56, aki: '済み', state: '先⾏'},
            { floor_cnt: 1, room_num: 110, tubo_cnt: 18.56, aki: '済み', state: '先⾏'},
            { floor_cnt: 1, room_num: 110, tubo_cnt: 18.56, aki: '済み', state: '先⾏'},
            { floor_cnt: 1, room_num: 110, tubo_cnt: 18.56, aki: '済み', state: '先⾏'},
            { floor_cnt: 1, room_num: 110, tubo_cnt: 18.56, aki: '済み', state: '先⾏'}
        ]
    },
    methods: {
        setBuilding: function(id) {
            alert(id)
        }
    }
})

Vue.config.debug = true;
Vue.config.devtools = true;

var app = new Vue({
    el: '#app',
    data(){
        return{
            apiUrl: '../../../api/common/redis.php',
            server: '', // 选中的服务器
            db    : '', // 选中的db
            key   : '', // 选中的key
            serves: [], // 所有服务器列表
            dbs   : [], // 选中的服务器所有的db列表
            keys  : [], // redis里所有的主键
            result: '', // 选中key后的value
            valType: 1, // 指定Redis键值的类型，默认是1:字符串； 2: set
            addserverModel  : false, // 是否显示新增服务器窗口
            configModel     : false, // 是否显示设置窗口
            isConfirmAction : false, // 是否显示确认操作窗口
            customModel     : false, // 是否显示自定义设置窗口
            customIP        : '',    // 自定义IP
            css   : {
                r_lf_ht: '600px',
                r_rt_ht: '600px'
            },
            configs: {
                columns: [
                    {
                        title: '标识',
                        slot : 'id',
                        sortable: true
                    },
                    {
                        title: '名称',
                        slot : 'name'
                    },
                    {
                        title: '服务器地址',
                        slot : 'server'
                    },
                    {
                        title: '端口',
                        slot : 'port'
                    },
                    {
                        title: '密码',
                        slot : 'password'
                    },
                    {
                        title: '操作',
                        slot : 'action',
                        width: 150,
                        align: 'center'
                    }
                ],
                data: [],
                editIndex : -1,  // 当前聚焦的输入框的行数
                deleteOne : -1,  // 被删除的行数
                editId    : '',  // 第一列输入框，当然聚焦的输入框的输入内容，与 data 分离避免重构的闪烁
                editName  : '',  // 第二列输入框
                editServer: '',  // 第三列输入框
                editPort  : '',  // 第四列输入框
                editPasswd: '',  // 第五列输入框
            },
            actionInfo : "",   // 确认操作窗口内容
        }
    },
    created: function() {
        this.getServers();
    },
    methods: {
        menuSelect: function(name) {
            // console.log(name);
            switch (name) {
                case "1":
                    this.configModel = true;
                    this.getConfigs();
                    break;
                case "8":
                    this.customModel = true;
                    break;
                default:
            }
            this.server = name;
            this.getDbs();

        },
        getServers: function() {
            var ctrl = this;
            let params = {
                step: 100
            };
            axios.get(this.apiUrl, {
                      params: params
                   })
                 .then(function (response) {
                     console.log(response);
                     if ( Array.isArray(response.data) ) {
                         ctrl.serves = response.data;
                     // if ( response.data && (typeof response.data === "object" ) ) {
                     //    let data = JSON.parse(JSON.stringify(response.data));
                     //    for (var key in data) {
                     //        let text = data[key] + "(" + key + ")";
                     //        let m = {
                     //            id: key,
                     //            text: text
                     //        }
                     //        ctrl.serves.push(m)
                     //    }
                     //    console.log(ctrl.serves);
                     } else {
                         ctrl.$Modal.error({
                            title: '服务端错误',
                            content: '请确认服务端是否配置正确！'
                         });
                     }
                 })
                 .catch(function (error) {
                     console.log(error);
                 });
        },
        getConfigs: function() {
            var ctrl = this;
            let params = {
                ca: 100
            };
            this.configs.editIndex = -1;
            axios.get(this.apiUrl, {
                      params: params
                   })
                 .then(function (response) {
                     if ( Array.isArray(response.data) ) {
                         ctrl.configs.data = response.data;
                         console.log(ctrl.configs.data);
                     } else {
                         if ( response.data ) {
                             ctrl.$Modal.error({
                                title: '服务端错误',
                                content: '请确认服务端是否配置正确！'
                             });
                         }
                     }

                 })
                 .catch(function (error) {
                     console.log(error);
                 });
        },
        onEdit:function(row, index) {
            this.configs.editId     = row.id;
            this.configs.editName   = row.name;
            this.configs.editServer = row.server;
            this.configs.editPort   = row.port;
            this.configs.editPasswd = row.password;
            this.configs.editIndex  = index;
        },
        onAdd() {
          this.addserverModel = true;
        },
        onSave:function(index) {
            var ctrl = this;
            let params = {
                ca      : 1,
                id      : this.configs.editId,
                name    : this.configs.editName,
                server  : this.configs.editServer,
                port    : this.configs.editPort,
                password: this.configs.editPasswd
            }
            if ( index >= 0 ) {
                params.ca = 2;
                if (this.configs.data[index].id != this.configs.editId) {
                    params.oid = this.configs.data[index].id;
                }
            }
            axios.get(this.apiUrl, {
                      params: params
                   })
                 .then(function (response) {
                     console.log(response);
                 })
                 .catch(function (error) {
                     console.log(error);
                 });

            if ( index >= 0 ) {
                this.configs.data[index].id       = this.configs.editId;
                this.configs.data[index].name     = this.configs.editName;
                this.configs.data[index].server   = this.configs.editServer;
                this.configs.data[index].port     = this.configs.editPort;
                this.configs.data[index].password = this.configs.editPasswd;
            } else {
                let record = params;
                delete record.action;
                this.configs.data.push(record);
            }
            this.configs.editIndex = -1;

            this.configs.editId     = "";
            this.configs.editName   = "";
            this.configs.editServer = "";
            this.configs.editPort   = "";
            this.configs.editPasswd = "";
        },
        onDelete:function(row, index) {
            this.actionInfo        = "删除后将不能恢复，确认要删除" + row.name + "[" + row.id + "]?";
            this.isConfirmAction   = true;
            this.configs.deleteOne = index;
        },
        onConfirmDelete:function() {
            let deleteOne = this.configs.data[this.configs.deleteOne];
            let id        = deleteOne.id;
            axios.get(this.apiUrl, {
                      params: {
                          ca: 3,
                          id: id
                      }
                   })
                 .then(function (response) {
                     console.log(response);
                 })
                 .catch(function (error) {
                     console.log(error);
                 });
            this.configs.data.splice(this.configs.deleteOne, 1);
            this.configs.deleteOne = -1;
        },
        getDbs: function() {
            var ctrl = this;
            let params = {
                step     : 1,
                server_id: this.server
            };
            axios.get(this.apiUrl, {
                      params: params
                   })
                 .then(function (response) {
                     // console.log(response);
                     if ( Array.isArray(response.data) ) {
                         ctrl.dbs = response.data;
                         console.log(ctrl.dbs);
                     } else {
                         ctrl.$Modal.error({
                            title: '服务端错误',
                            content: '请确认服务端是否配置正确！'
                         });
                     }

                 })
                 .catch(function (error) {
                     console.log(error);
                 });
        },
        getKeys: function(name) {
            var ctrl = this;
            this.db = name;
            let params = {
                step     : 2,
                server_id: this.server,
                db       : this.db
            };
            axios.get(this.apiUrl, {
                      params: params
                   })
                 .then(function (response) {
                     ctrl.keys = response.data;
                     console.log(ctrl.keys);
                 })
                 .catch(function (error) {
                     console.log(error);
                 });
        },
        getKeyDetail: function(key) {
            this.key = key;
            var ctrl = this;
            this.valType = 1;
            let params = {
                step     : 3,
                server_id: this.server,
                db       : this.db,
                key      : this.key
            };
            axios.get(this.apiUrl, {
                      params: params
                   })
                 .then(function (response) {
                     var data = response.data;
                     var result = "";
                     if ( data ) {
                         if ( Array.isArray(data) ) {
                             result = data.join("\r\n");
                             ctrl.valType = 2;
                         } else {
                             result = data.trim();
                             if ( ctrl.isJson(result) == "object" ) {
                                 result = JSON.stringify(JSON.parse(result), null, 2);
                                 result = result.replace(/ /g, "  ");
                             }
                         }
                     }
                     ctrl.result = result;
                 })
                 .catch(function (error) {
                     console.log(error);
                 });
        },
        updateKey: function() {
            var ctrl = this;
            let result = this.result;
            if ( result ) {
                if ( this.valType == 1 ) {
                    result = result.replace(/\s+/g, "");
                } else if ( this.valType == 2 ) {
                    result = result.trim().replace(/\s+/g, "|");
                    console.log(result);
                }
            }
            let params = {
                step     : 4,
                server_id: this.server,
                db       : this.db,
                key      : this.key,
                valType  : this.valType,
                result   : result
            };
            axios.get(this.apiUrl, {
                      params: params
                   })
                 .then(function (response) {
                     var data = response.data;
                     var result = "";
                     if ( data ) {
                         if ( Array.isArray(data) ) {
                             result = data.join("\r\n");
                             ctrl.valType = 2;
                         } else {
                             result = data.trim();
                             if ( ctrl.isJson(result) == "object" ) {
                                 result = JSON.stringify(JSON.parse(result), null, 2);
                                 result = result.replace(/ /g, "  ");
                             }
                         }
                     }
                     ctrl.result = result;
                 })
                 .catch(function (error) {
                     console.log(error);
                 });

        },
        /**
         * 判断是否json
         */
        isJson: function(string) {
            try {
                if ( typeof JSON.parse(string) == 'object' )
                    return true;
                return false;
            } catch (e) {
                console.log(e);
                return false;
            }
        },
        initLayout: function() {
            let offsetH  = 195;
            let c_height = (window.innerHeight - offsetH) +'px';
            // 内容区域左侧、右侧高度
            this.css.r_lf_ht = c_height;
            this.css.r_rt_ht = c_height;
            // 内容区域左侧内容高度
            let l_height = (window.innerHeight - 300) +'px !important';
            $(".left_area .ivu-list-container").css("cssText", "min-height:" + l_height);
            // $(".left_area .ivu-list-container").css("cssText", "height:auto");
            // 内容区域又侧内容高度
            let r_height = (window.innerHeight - 250) +'px !important';
            $(".right_area textarea").css("cssText", "min-height:" + r_height + ";max-height:" + r_height);
            if (window.innerHeight < 800) {
                $(".right_area").css("cssText", "position:relative;width:59%;right:5px;");
            }
        }
    },
    filters: {
        pretty: function(value) {
            if (value) {
                return JSON.stringify(JSON.parse(value), null, 2);
            }
            return value;
        }
    },
    watch : {
        reportInfo: function(newValue) {
            this.reportInfo = newValue;
        }
    },
    mounted() {
        this.initLayout();

        window.onresize = () => {
            return (() => {
                this.initLayout();
            })()
        }
    }
});

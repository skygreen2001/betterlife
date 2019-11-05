Vue.config.debug = true;
Vue.config.devtools = true;

var app = new Vue({
    el: '#app',
    data(){
        return{
            server: '', // 选中的服务器
            db    : '', // 选中的db
            key   : '', // 选中的key
            dbs   : [],
            keys  : [], // redis里所有的主键
            result: '',
            isShowMenuConfig: false,
            configModel: false,
            customModel: false,
            customIP   : '', // 自定义IP
            css   : {
                r_lf_ht: '600px',
                r_rt_ht: '600px'
            }
        }
    },
    methods: {
        menuSelect: function(name) {
            // console.log(name);
            switch (name) {
                case "1":
                    this.configModel = true;
                    break;
                case "5"://v3
                    this.server = 'v3.itasktour.com';
                    this.getDbs();
                    break;
                case "6"://debug
                    this.server = 'dev.itasktour.com';
                    this.getDbs();
                    break;
                case "7"://本地
                    this.server = '127.0.0.1';
                    this.getDbs();
                    break;
                case "8":
                    this.customModel = true;
                    break;
                default:
            }
        },
        show: function() {
            this.visible = true;
        },
        getDbs: function() {
            var ctrl = this;
            let params = {
                step  : 1,
                server: this.server
            };
            axios.get('../../../api/common/redis.php', {
                      params: params
                   })
                 .then(function (response) {
                     console.log(response);
                     ctrl.dbs = response.data;
                     console.log(ctrl.dbs);
                 })
                 .catch(function (error) {
                     console.log(error);
                 });
        },
        getKeys: function(name) {
            var ctrl = this;
            this.db = name;
            let params = {
                step  : 2,
                server: this.server,
                db: this.db
            };
            axios.get('../../../api/common/redis.php', {
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
            let params = {
                step  : 3,
                server: this.server,
                db: this.db,
                key: this.key
            };
            axios.get('../../../api/common/redis.php', {
                      params: params
                   })
                 .then(function (response) {
                     let result = response.data;
                     if (result) {
                         result = JSON.stringify(JSON.parse(result), null, 2);
                         result = result.replace(/ /g, "  ");
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
            if (result) {
                result = result.replace(/\s+/g, "");
                // result = result.replace(/\"/g, "'");
            }
            let params = {
                step  : 4,
                server: this.server,
                db: this.db,
                key: this.key,
                result: result
            };
            axios.get('../../../api/common/redis.php', {
                      params: params
                   })
                 .then(function (response) {
                     let result = response.data;
                     if (result) {
                         result = JSON.stringify(JSON.parse(result), null, 2);
                         result = result.replace(/ /g, "  ");
                     }
                     ctrl.result = result;
                 })
                 .catch(function (error) {
                     console.log(error);
                 });

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

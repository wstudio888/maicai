var server = require('../../../../utils/server');
var cPage = 0;
var ctype = "NO";
Page({
  data: {
    table: 1,
  },
  tabClick: function (e) {
    var index = e.currentTarget.dataset.index
    var types = ["0","1", "2", "3","4"]
    var classs = ["text-normal", "text-normal", "text-normal", "text-normal", "text-normal"]
    classs[index] = "text-select"
    this.setData({ tabClasss: classs, tab: index })
    cPage = 0;
    ctype = types[index];
    this.data.orders = [];
    this.getOrderLists(types[index], cPage);
  },

  onReachBottom: function () {
    this.getOrderLists(ctype, ++cPage);
    wx.showToast({
      title: '加载中',
      icon: 'loading'
    })
  },
  onPullDownRefresh: function () {
    cPage = 0;
    this.data.orders = [];
    this.getOrderLists(ctype, 0);
  },
  data: {
    orders: [],
    tabClasss: ["text-select", "text-normal", "text-normal", "text-normal", "text-normal"],
  },
  getOrderLists: function (ctype, page) {
    var that = this;
    // var user_id = getApp().globalData.userInfo.user_id
    var user_id = getApp().globalData.userInfo.user_id;
    server.getJSON('/User/detailed/user_id/' + user_id + "/type/" + ctype + "/page/" + page, function (res) {
      var datas = res.data.result;

      var ms = that.data.orders
      for (var i in datas) {
        ms.push(datas[i]);
      }
      wx.stopPullDownRefresh();
      that.setData({
        statustype: ctype,
        orders: ms
      });
    });
  },
  onShow: function (e) {
    // 页面显示
    cPage = 0;
  },
  onLoad: function (e) {
    var table = e.table;
    this.setData({
      table: table
    });
    if (table > 0) {
      var index = table
      var types = ["0", "1", "2", "3", "4"]
      var classs = ["text-normal", "text-normal", "text-normal", "text-normal", "text-normal"]
      classs[index] = "text-select"
      this.setData({ tabClasss: classs, tab: index })
      cPage = 0;
      ctype = types[index];
      this.data.orders = [];
      this.getOrderLists(types[index], cPage);
    } else {
      this.getOrderLists(0, cPage);
    }
  }
});
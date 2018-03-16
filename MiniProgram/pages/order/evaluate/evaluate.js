var server = require('../../../utils/server');
var textcontent =  [];
var checkbut = [];
Page({
  data: {
    table: 1,
    focus:true
  },
  onShow: function (e) {
    // 页面显示
    // var orderId = e.currentTarget.orderid;

    // this.getOrderFind(orderId);
  },

  onLoad: function (e) {
    var orderId = e.orderid;
    this.getOrderFind(orderId);
  },

  getOrderFind: function (orderId) {
    var that = this;
    var user_id = getApp().globalData.userInfo.user_id

    server.getJSON('/User/getOrderFind/user_id/' + user_id + "/orderid/" + orderId, function (res) {
      var ms = res.data.result.result
      that.setData({
        orders: ms
      });
    });
  },
  
  bindButtonTap: function (e) {
    var rec_id = e.currentTarget.dataset.index;
    textcontent[rec_id] = e.detail.value;
    console.info(e.currentTarget.dataset.index);
    this.setData({
      textcontent: textcontent
    })
  },

  textok:function(e){
    this.setData({
      focus: false
    })
    var that = this;
    var user_id = getApp().globalData.userInfo.user_id
    var rec_id = e.currentTarget.dataset.index;
    var textcontent = this.data.textcontent;
    if (checkbut[rec_id] == 1){
      wx.showToast({
        title: '请勿重复评论',
        duration: 1000
      });
      return false;
    }
    server.postJSON('/User/textok/user_id/' + user_id + "/rec_id/" + rec_id, { textcontent: textcontent[rec_id]}, function (res) {
      var ms = res.data.result.result
      if (res.data.status == 200){
        checkbut[rec_id] = 1;
        that.setData({
          checkbut: checkbut
        })
        wx.showToast({
          title: '评论成功',
          duration: 1000
        });
      }
    });
  },



  toTop: function () {
    wx.pageScrollTo({ scrollTop: 0 });
  }
  
});

var server = require('../../../../utils/server');

Page({
  onLoad: function (options) {
    var that = this;
    that.getWxRwm();
  },
  getWxRwm: function () {
    var user_id = getApp().globalData.userInfo.user_id;

    var that = this;
    server.getJSON('/User/checkrwm?user_id=' + user_id, function (res) {
      that.setData({
        rwmimg: res.data.data
      });
    });

    that.setData({
      rwmimg: rwmimg
    });
  },
  imgfd: function (e) {
    var current = e.target.dataset.index
    var urls = [];
    urls[0] = current;
    wx.previewImage({
      current: current,
      urls: urls,
      fail: function () {
        console.log('fail')
      },
      complete: function () {
        console.info("点击图片了");
      }
    })
  }, onShareAppMessage: function () {
    return {
      title: '买菜平台',
      desc: '一起来赚钱吧！',
      path: '/pages/index/index?kid=' + getApp().globalData.userInfo.user_id
    }
  }
});



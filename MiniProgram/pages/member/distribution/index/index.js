const AV = require('../../../../utils/av-weapp.js')
var server = require('../../../../utils/server');
var app = getApp()
var maxTime = 60
var interval = null
var currentTime = -1 //倒计时的事件（单位：s）  

Page({
  getUserFxInfo: function () {
    var that = this;
    var user_id = getApp().globalData.userInfo.user_id
    server.getJSON('/User/getUserFxInfo/user_id/' + user_id, function (res) {
      console.info(res.data);
      that.setData({
        userData: res.data.data
      });
    });
  },
  onLoad: function (options) {
    var login = app.globalData.login;
    var that = this;

    wx.getSystemInfo({
      success: function (res) {
        that.setData({ height: res.windowHeight })
      }
    })
  },
  onShow: function () {
    var that = this;
    var login = app.globalData.login;
    var that = this;
    this.getUserFxInfo();
    this.setData({ login: login });
    // 调用小程序 API，得到用户信息
    wx.getUserInfo({
      success: ({ userInfo }) => {
        that.setData({
          userInfo: userInfo
        });
        app.globalData.nickName = userInfo.nickName;
      }
    });
  },

  //分销佣金
  navigateTomoney:function(){
    wx.navigateTo({
      url: '../money/money'
    });
  },

  // 推广二维码
  navigateToRwm: function () {
    wx.navigateTo({
      url: '../rwm/index'
    });
  },

  // 我的下线
  navigateToOffline: function () {
    wx.navigateTo({
      url: '../offline/offline',
    })
  },

   // 分销订单
  navigateToOrder: function () {
    wx.navigateTo({
      url: '../order/order',
    })
  },

  // 提现明细
  navigateToDetailed: function () {
    wx.navigateTo({
      url: '../detailed/detailed',
    })
  }

});



// moeny.js
var server = require('../../../../utils/server');
Page({

  /**
   * 页面的初始数据
   */
  data: {
  
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.getUserInfoMoeny();
  },

  navigateToDetailed:function(){
    wx.navigateTo({
      url: '../detailed/detailed',
    })
  },
  navigateToWithdrawals:function(){
    wx.navigateTo({
      url: '../withdrawals/withdrawals',
    })
  },
  /**
   * 用户佣金信息
   */
  getUserInfoMoeny:function(){
     var that = this;
    //  var user_id = getApp().globalData.userInfo.user_id
     var user_id = getApp().globalData.userInfo.user_id;
     server.getJSON('/User/getUserInfoMoney/user_id/' + user_id, function (res) {
        that.setData({
          user: res.data.result
        })
     });
  },

})
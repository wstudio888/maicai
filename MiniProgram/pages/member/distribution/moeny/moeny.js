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

  /**
   * 用户佣金信息
   */
  getUserInfoMoeny:function(){
     var that = this;
    //  var user_id = getApp().globalData.userInfo.user_id
     var user_id = getApp().globalData.userInfo.user_id;
     server.getJSON('/User/getUserInfoMoeny/user_id/' + user_id, function (res) {
        that.setData({
          user: res.data.result
        })
     });
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  }
})
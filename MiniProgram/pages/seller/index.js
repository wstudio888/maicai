var server = require('../../utils/server');
var app = getApp();

Page({
  data:{},
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    var that = this;
    server.getJSON("/Store/getStoreClass",function(res){
            var store_class = res.data;
            for(var i = 0 ; i< store_class.length;i++){
              if(i == 0)
              {
                 store_class[i].select = 1;
                 that.getStoreList(store_class[i].sc_id);
                 }
              else{
                store_class[i].select = 0;
              }
            }
            that.setData({store_class:store_class});
    });
  },
  getStoreList:function(sc_id){
    var that = this;
      server.getJSON("/Store/getStores",function(res){
            var stores = res.data;
            
            that.setData({stores:stores});
    });
  },
  // 关注店铺
  inSeller:function(e){
    var seller_id = e.currentTarget.dataset.id;
    console.log(seller_id);
    
    var user_id = getApp().globalData.userInfo.user_id
    var ctype = 0;

    server.getJSON('/Goods/collectSeller/user_id/' + user_id + "/seller_id/" + seller_id + "/type/" + ctype, function (res) {
      wx.showToast({ title: res.data.msg, icon: 'success', duration: 2000 })
    });
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  },
  goods:function(e){
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: 'goods?id=' + id,
      success: function(res){
        // success
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
  take:function(e){
    var phone = e.currentTarget.dataset.phone;
    wx.makePhoneCall({
  phoneNumber: phone //
})
  },
  onClickClass:function(e){
    var class_id = e.currentTarget.dataset.id;
    var store_class = this.data.store_class;
    for(var i = 0 ; i< store_class.length;i++){
              if(store_class[i].sc_id == class_id)
              {
                 store_class[i].select = 1;
                 this.getStoreList(store_class[i].sc_id);
              }
              else{
                store_class[i].select = 0;
              }
            }
           this.setData({store_class:store_class});
  },

  onShareAppMessage: function () {
    return {
      title: '买菜平台',
      desc: '买菜平台',
      path: '/pages/index/index'
    }
  },
})
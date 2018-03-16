
var server = require('../../utils/server');
var WxParse = require('../../wxParse/wxParse.js');
var seat;
Page({
  data: {
    banner: [],
    goods: [],
    bannerHeight: Math.ceil(290.0 / 650.0 * getApp().screenWidth),
    pstatus: 0,
    showModalStatus: false
  },
  showCoupon: function (e) {
    wx.navigateTo({
      url: '../member/coupon/index',
      success: function (res) {
        // success
      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })
  },
  showOrder: function (e) {
    wx.navigateTo({
      url: '../order/list/list',
      success: function (res) {
        // success
      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })
  },
  showCollect: function (e) {
    wx.navigateTo({
      url: '../member/collect/collect',
      success: function (res) {
        // success
      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })
  },
  showMine: function (e) {
    wx.switchTab({
      url: "../member/index/index"
    });
  },
  showSeller: function (e) {
    wx.navigateTo({
      url: '../seller/index',
      success: function (res) {
        // success
      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })
  },
  search: function (e) {
    wx.navigateTo({
      url: "../search/index"
    });
  },
  showCarts: function (e) {
    wx.switchTab({
      url: "../cart/cart"
    });
  },
  onLoad: function (options) {
    var thal = this;
    //seat = options.seat;
    //wx.showToast({title:seat+"seat"});
    this.loadBanner();
    //this.loadMainGoods();
    this.getInviteCode(options);

    var app = getApp(); //全局

    var fid = options.fid  //分销id
    if (fid) {
      getApp().globalData.fid = fid
    }
    //检查openid,不存在执行注册用户
    app.getOpenId(function () {
      var openId = getApp().globalData.openid;
      server.getJSON("/User/validateOpenid", { openid: openId }, function (res) {
        if (res.data.code == 200) {
          getApp().globalData.userInfo = res.data.data;
          getApp().globalData.login = true;
          thal.setData({
            user_id: res.data.data.user_id
          })

        } else {
          if (res.data.code == '400') {
            app.register(function () {
              getApp().globalData.login = true;
            });
          }
        }
      });
    });
  },
  /**
   * 分销出来的
   */
  setFxUser: function (fid) {
    var user_id = getApp().globalData.userInfo.user_id
    server.getJSON('/User/regfx/user_id/' + user_id, function (res) {

    });
  },
  getInviteCode: function (options) {
    if (options.uid != undefined) {
      wx.showToast({
        title: '来自用户:' + options.uid + '的分享',
        icon: 'success',
        duration: 2000
      })
    }
  },
  loadBanner: function () {
    var that = this;

    server.getJSON("/Index/home", function (res) {
      var banner = res.data.result.ad;
      var newlist = res.data.result.newlist;
      var coupon = res.data.result.coupon;
      var cuxiao = res.data.result.cuxiao;
      var about = res.data.result.about;
      var article = res.data.result.about.content;
      var category = res.data.result.category;
      var category2 = res.data.result.category2;

      var hotarticle = res.data.result.hot.content;
      var hot = res.data.result.hot;

      WxParse.wxParse('hotarticle', 'html', hotarticle, that, 5);
      WxParse.wxParse('article', 'html', article, that, 5);
      that.setData({
        banner: banner,
        newlist: newlist,
        coupon: coupon,
        category: category,
        coupon: coupon,
        about: about,
        hot: hot,
        cuxiao: cuxiao,
        category2: category2
      });
    });
  },
  loadMainGoods: function () {
    var that = this;
    var query = new AV.Query('Goods');
    query.equalTo('isHot', true);
    query.find().then(function (goodsObjects) {
      that.setData({
        goods: goodsObjects
      });
    });
  },
  onShow: function () {

  },
  clickBanner: function (e) {
    var goodsId = e.currentTarget.dataset.goodsId;
    wx.navigateTo({
      url: "../goods/detail/detail?objectId=" + goodsId
    });
  },
  showDetail: function (e) {
    var goodsId = e.currentTarget.dataset.goodsId;
    wx.navigateTo({
      url: "../goods/detail/detail?objectId=" + goodsId
    });
  },
  showCategories: function () {
    // wx.navigateTo({
    // 	url: "../category/category"
    // });
    wx.switchTab({
      url: "../category/category"
    });
  },
  showGroupList: function () {
    wx.navigateTo({
      url: "../goods/grouplist/list"
    });
  },
  hotbut: function (e) {
    var id = e.currentTarget.dataset.index;
    wx.navigateTo({
      url: "../seller/goods?id=" + id
    });
  },
  /**
   * 获取优惠卷
   */
  getcoupon: function (e) {
    var thal = this;
    var couponid = e.currentTarget.dataset.couponId;
    var user_id = getApp().globalData.userInfo.user_id;
    var store_id = e.currentTarget.dataset.storeId;
    var coupon = this.data.coupon;

    server.postJSON('/User/getcoupon', { user_id: user_id, couponid: couponid, store_id: store_id }, function (res) {
      if (res.data.status == 1) {
        wx.showToast({ title: "领取成功" });
        for (var i = 0; i < coupon.length; i++) {
          if (couponid == coupon[i]['id']) {
            coupon[i]['disabled'] = true;
          }
        }
        thal.setData({
          coupon: coupon
        })
      } else {
        thal.setData({
          count: 1500,
          toastText: res.data.msg,
          toastText1: 'Toast'
        });
        thal.showModal();
        thal.showToast();
        if (res.data.status == -2) {
          for (var i = 0; i < coupon.length; i++) {
            if (couponid == coupon[i]['id']) {
              coupon[i]['disabled'] = true;
            }
          }
          thal.setData({
            coupon: coupon
          })
        }
      }
    });
  },

  onShareAppMessage: function () {
    return {
      title: '买菜平台',
      desc: '电商系统',
      path: '/pages/index/index'
    }
  },

  showModal: function () {
    // 显示遮罩层
    var animation = wx.createAnimation({
      duration: 200,
      timingFunction: "linear",
      delay: 0
    })
    this.animation = animation
    animation.translateY(0).step()
    this.setData({
      animationData: animation.export(),
      showModalStatus: true
    })
    // setTimeout(function () {
    //   animation.translateY(0).step()
    //   this.setData({
    //     animationData: animation.export()
    //   })
    // }.bind(this), 200)
    console.log("准备执行");
    timer = setTimeout(function () {
      if (this.data.showModalStatus) {
        this.hideModal();
        console.log("是否执行");
      }
    }.bind(this), 3000)
  },
  clickbtn: function () {
    //设置toast时间，toast内容  


  },
  hideModal: function () {
    if (timer != null) {
      clearTimeout(timer);
      timer = null;
    }
    // 隐藏遮罩层
    var animation = wx.createAnimation({
      duration: 200,
      timingFunction: "linear",
      delay: 0
    })
    this.animation = animation
    animation.translateY(200).step()
    this.setData({
      animationData: animation.export(),
    })
    setTimeout(function () {
      animation.translateY(0).step()
      this.setData({
        animationData: animation.export(),
        showModalStatus: false
      })
    }.bind(this), 200)
  },
  showToast: function () {
    var _this = this;
    // toast时间  
    _this.data.count = parseInt(_this.data.count) ? parseInt(_this.data.count) : 1000;
    // 显示toast  
    _this.setData({
      showModalStatus: true,
    });
    // 定时器关闭  
    setTimeout(function () {
      _this.setData({
        showModalStatus: false
      });
    }, _this.data.count);
  },

  /**
   * 导航跳转
   */
  showSwitchTab: function (event) {
    var url = event.currentTarget.dataset.indexUrl;
    wx.switchTab({
      url: url
    });
  },
  showNavigateTo: function (event) {
    var url = event.currentTarget.dataset.indexUrl;
    wx.navigateTo({
      url: url
    });
  }
})
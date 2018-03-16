var server = require('../../../../utils/server');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    showstatus: true,
    showstatus2: false,
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    //加载提现余额
    this.getusermoney();
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  getusermoney: function () {
    var thal = this;
    var user_id = getApp().globalData.userInfo.user_id // 用户ID
    server.getJSON("/Distribution/money/user_id/" + user_id, function (res) {
      thal.setData({
        usermoney: res.data.result
      })
    });
  },

  switch1Change: function (e) {
    if (e.detail.value) {
      this.setData({
        showstatus2: false,
        showstatus: true,
      })
    } else {
      this.setData({
        showstatus2: true,
        showstatus: false,
      })
    }
    this.setData({
      Setbankname: undefined,
      SetaccountBank: undefined,
      SetaccountName: undefined,
    });
  },

  switch2Change: function (e) {
    if (e.detail.value) {
      this.setData({
        showstatus2: true,
        showstatus: false,
      })
    } else {
      this.setData({
        showstatus2: false,
        showstatus: true,
      })
    }
    this.setData({
      Setbankname: undefined,
      SetaccountBank: undefined,
      SetaccountName: undefined,
    });
  },

  Setbankname: function (e) {
    var Setbankname = e.detail.value;
    this.setData({ Setbankname: Setbankname });
  },

  Setbankname2: function (e) {
    var Setbankname2 = e.detail.value;
    this.setData({ Setbankname2: Setbankname2 });
  },

  SetaccountBank: function (e) {
    var SetaccountBank = e.detail.value;
    this.setData({ SetaccountBank: SetaccountBank });
  },

  SetaccountBank2: function (e) {
    var SetaccountBank2 = e.detail.value;
    this.setData({ SetaccountBank2: SetaccountBank2 });
  },

  SetaccountName: function (e) {
    var SetaccountName = e.detail.value;
    this.setData({ SetaccountName: SetaccountName });
  },

  /**
   * 确认提现
   */
  submitok: function () {
    var user_id = getApp().globalData.userInfo.user_id // 用户ID
    var bank_name = this.data.Setbankname; // 银行名称 如支付宝 微信 中国银行 农业银行等
    var account_bank = this.data.SetaccountBank; // 银行账号
    var account_name = this.data.SetaccountName; // 银行账户名 可以是支付宝可以其他银行
    var money = this.data.usermoney.out_money;

    if (money < 1) {
      wx.showToast({ title: '暂无可提现余额' });
      return;
    }

    // 验证
    if (this.data.showstatus) {  //为真,验证转账到微信
      var subtype = 1;  //提现到微信

      if (bank_name == undefined) {
        wx.showToast({ title: '请输入微信号' });
        return;
      }
      //两次微信是否相同
      if (bank_name != this.data.Setbankname2) {
        wx.showToast({ title: '两次输入微信号不相同' });
        return;
      }
      server.postJSON("/Distribution/submitmoney", { account_bank: bank_name, user_id: user_id, subtype: subtype, money: money }, function (res) {
        wx.showToast({ title: '提交成功' });
        wx.navigateTo({
          url: '../detailed/detailed',
        })
      });
    } else {
      var subtype = 2;

      if (bank_name == undefined) {
        wx.showToast({ title: '请输入银行名称' });
        return;
      }

      if (account_bank == undefined) {
        wx.showToast({ title: '请输入银行卡号' });
        return;
      }

      if (account_bank != this.data.SetaccountBank2) {
        wx.showToast({ title: '两次输入银行卡号不相同' });
        return;
      }

      if (account_name == undefined) {
        wx.showToast({ title: '请输入真实姓名' });
        return;
      }

      server.postJSON("/Distribution/submitmoney", { bank_name: bank_name, user_id: user_id, subtype: subtype, money: money, account_name: account_name, account_bank: account_bank }, function (res) {
        if (res.data.status == 200) {
          wx.showToast({ title: '提交成功' });
          wx.navigateTo({
            url: '../detailed/detailed',
          })
        }
      });
    }
  }
})
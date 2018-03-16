var server = require('../../../utils/server');
// 提取商品详情描述的图片转换成一个数组
var getImgByStr = function (str) {
  var img = [];
  var current = 0;
  var count = 0;
  console.log('str is',str);
  while (true) {
    var i1 = str.indexOf('src=', current) + 4;
    if (i1 == 3 || count > 10) {
      break;
    }
    var i2 = str.indexOf('"', i1 + 1);
    var src = str.substring(i1 + 1, i2);
    if (src.indexOf('woshangapp') == -1) {
      src = 'https://lihe.woshangapp.com' + src
    }
    img.push(src);
    current = i2;
    count++;
  }
  return img;
};

Page({
  data: {
    main: [],
    img: [],
    logo:[],
  },
	onLoad: function (options) {
    var that = this;
    server.getJSON("/Index/aboutUs", function (res) {
      console.log(res.data);
      var main = res.data.result.description;
      var logo = res.data.result.logo;
      var img = getImgByStr(res.data.result.content);
      console.log(img);
      console.log(2);
      that.setData({
        main: main,
        img: img,
        logo:logo,
      });
    });
	},
  
  toTop: function () {
    wx.pageScrollTo({ scrollTop: 0 });
  }
});
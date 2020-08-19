$.fn.OssUpload = function (attribute, dir, url, result_src, result_value) {
	document.getElementById(attribute).addEventListener('change', function (e) {
          let file = e.target.files[0];
          var ext = file.name.substr(file.name.lastIndexOf(".")).toLowerCase();
            var timestamp = new Date().getTime();
            var name = timestamp + RndNum(6) + ext;
          let storeAs = dir + name;
          // console.log(file.name + ' => ' + storeAs);
          // OSS.urlib是sdk内部封装的发送请求的逻辑，开发者完全可以使用任何发请求的库向`sts-server`发送请求
          OSS.urllib.request(url, {method: 'GET'}, (err, response) => {
              if (err) {
                return alert(err);
              }
              try {
                result = JSON.parse(response);
              } catch (e) {
                return alert('parse sts response info error: ' + e.message);
              }
              let client = new OSS({
                region: 'oss-cn-shenzhen',
                accessKeyId: result.AccessKeyId,
                accessKeySecret: result.AccessKeySecret,
                stsToken: result.SecurityToken,
                bucket: 'enjoycar'
              });
              //storeAs表示上传的object name , file表示上传的文件
              client.multipartUpload(storeAs, file).then(function (result) {
                $(result_src).attr('src', "http://enjoycar.oss-cn-shenzhen.aliyuncs.com/" + storeAs);
                $(result_value).attr("value","http://enjoycar.oss-cn-shenzhen.aliyuncs.com/" + storeAs);
                // $("#driver_file").val("http://enjoycar.oss-cn-shenzhen.aliyuncs.com/" + storeAs);
              }).catch(function (err) {
                console.log(err);
              });
            });
        })
}
